<?php

error_reporting(E_ERROR);

require 'inc/settings.php';





/* DON'T EDIT BELOW THIS LINE */
require 'inc/class.spinner.php';





$st = $db->prepare('SELECT * FROM domains WHERE id=1');
$st->execute();

$domain = $st->fetch(PDO::FETCH_ASSOC);



$dominio = $domain['domain'];





$settings['domain_id'] = $domain['id'];
$settings['host'] = $domain['domain'];

$settings['menu'] = $db->query('SELECT * FROM menu WHERE domain_id='.$domain['id'].' ORDER BY position ASC');


foreach($db->query('SELECT * FROM settings WHERE domain_id='.$domain['id']) as $entry){

    $settings[$entry['key']] = $entry['value'];

}

$estilo_menu = $settings['style_menu']; //OPCIONES: vertical,horizontal



$amz_tag = $settings['amazon_tag'];
$api_key = $settings['apikey'];
$api_secret = $settings['apisecret'];
$cache_directory = $settings['cachedir']; 
$interlinking = intval($settings['interlinking']);





if(isset($_GET['k'])){

    $st = $db->prepare('SELECT * FROM keywords WHERE domain_id=:domain_id AND slug=:keyword AND parent_id IS NULL');
    $st->execute(array('domain_id'=> $domain['id'], 'keyword' => $_GET['k']));
    
    $keyword = $st->fetch(PDO::FETCH_ASSOC);


    if(empty($keyword)){
        header("HTTP/1.0 404 Not Found");
        die();
    }



    $settings['main_amazon_term'] = $keyword['amazon_term'];
    $settings['main_keyword'] = $keyword['keyword'];
    $settings['slug'] = $keyword['slug'];




    $st = $db->prepare('SELECT * FROM keywords WHERE domain_id=:domain_id AND parent_id=:keyword_id');
    $st->execute(array('domain_id'=> $domain['id'], 'keyword_id' => $keyword['id']));
    
    $settings['keywords'] = $st->fetchAll(PDO::FETCH_ASSOC);


    if(!empty($settings['source_text'])){


        $spintext_keyword = $settings['source_text'];
        $spintitle_keyword = $keyword['keyword'];

        $check_keyword_text = $db->query('SELECT id, spintext, spintitle from contents WHERE keyword_id='.$keyword['id'])->fetch();


        if(!empty($check_keyword_text)){
            $spintext_keyword = ($check_keyword_text['spintext']);
            $spintitle_keyword = ($check_keyword_text['spintitle']);
        }


        $spintax = new Spintax();
        $spintext = $spintax->process(str_replace('__KEYWORD__', $keyword['keyword'],$spintext_keyword));
        $spintitle = $spintax->process(str_replace('__KEYWORD__', $keyword['keyword'],$spintitle_keyword));
        
        

        if(!$check_keyword_text['id']){
            $gentext = $db->query('SELECT spintext, spintitle from generated_spintext WHERE content_id='.$keyword['id'])->fetch();

            
                if(!empty($gentext)){

                    $data[] = array('spintitle' => $gentext['spintitle'], 'spintext' => ($gentext['spintext']));

                }else{

                    $data[] = array('spintitle' => $spintitle, 'spintext' => ($spintext));
                    
                    $db->query('INSERT INTO generated_spintext (id, content_id, spintitle, spintext) VALUES (NULL,'.$keyword['id'].',"'.$keyword['keyword'].'","'.($spintext).'")');

                    



                }
        }else{
            $data[] = array('spintitle' => $spintitle, 'spintext' => ($spintext));
        }




        
    }    

    

    foreach($settings['keywords'] as $entry){

            $data[] = process_text($entry['id'],$entry['keyword']);
    
    }



}



$ilink = $db->query('SELECT DISTINCT slug, keyword FROM keywords WHERE indexed=1 ORDER BY RAND() LIMIT '. $interlinking)->fetchAll();



function process_text($id, $keyword){
    global $db;

    $temp = $db->query('SELECT spintext, spintitle FROM contents WHERE keyword_id='.$id)->fetch();
    if(!empty($temp)){

        $spintax = new Spintax();
        $string = str_replace('__KEYWORD__', $keyword,$temp);
        return $spintax->process($string);
    }



}







function search_amazon_products($keyword_link, $is_menu=0){
    global $amz_tag, $db;

        $st = $db->prepare('SELECT * FROM keywords WHERE slug=:keyword');
        $st->execute(array('keyword' => $keyword_link));
        $keyword = $st->fetch(PDO::FETCH_ASSOC);


    if($is_menu == 0){
        $keyword_slug = $keyword['slug'];
    }else{
        $keyword_slug = str_replace("-"," ", $keyword_link);

    }

    



    if($keyword['cache_type'] === "html"){



        if(file_exists("inc/cache/".sha1($keyword_slug).".html")){


            $html = file_get_contents("inc/cache/".sha1($keyword_slug).".html");
            
            @($domd = new DOMDocument())->loadHTML($html);
            $xp=new DOMXPath($domd);

            $xquery = '//div[@data-component-type="s-search-result"]';           
            $links = $xp->query($xquery);

            foreach($links as $l){
                    if(@(trim($xp->query('.//span[@class="s-label-popover-default"]',$l)->item(0)->textContent)) != "Patrocinado"){

                        $lpart = explode("/dp/",trim($xp->query('.//h2/a',$l)->item(0)->getAttribute('href')));


                        $products[] = array(
                            'asin' => $l->getAttribute("data-asin"),
                            'title' => trim($xp->query('.//h2',$l)->item(0)->textContent),
                            'image' => $xp->query('.//img[@class="s-image"]', $l)->item(0)->getAttribute("src"),
                            'link' => 'https://www.amazon.es'.$lpart[0].'/dp/'.$l->getAttribute("data-asin").'?tag='.$amz_tag,
                            'price' => trim($xp->query('.//span[@class="a-offscreen"]',$l)->item(0)->textContent)
                        );

                    }
                
            }

            return $products;
        }else{

            return array();
        }
    }else{


        if(file_exists("inc/cache/".sha1($keyword_slug).".json")){



            $json = json_decode(file_get_contents("inc/cache/".sha1($keyword_slug).".json"));
            




            foreach($json->SearchResult->Items as $entry){
                        $products[] = array(
                            'asin' => $entry->ASIN,
                            'title' => $entry->ItemInfo->Title->DisplayValue,
                            'image' => $entry->Images->Primary->Large->URL,
                            'link' => $entry->DetailPageURL,
                            'price' => $entry->Offers->Listings[0]->Price->Amount." ".($entry->Offers->Listings[0]->Price->Currency?"€":$entry->Offers->Listings[0]->Price->Currency)
                        );
            }


            return $products;
        }else{

            return array();
        }





    }




}









function trimString ( $cadena, $longitud = 128 ) {
    //Eliminamos las etiquetas HTML
    $cadena = trim ( strip_tags ( $cadena ) );
    
    if ( strlen ( $cadena ) > $longitud ) {
        //Acortamos la cadena a la longitud dada
        $cadena = substr ( $cadena, 0, $longitud + 1 );
        
        //Expresión regular de espacio en blanco
        $regExp = "/[\s]|&nbsp;/";              
        
        //Dividimos la cadena en palabras y la guardamos en un array        
        $palabras = preg_split ( $regExp, $cadena, -1, PREG_SPLIT_NO_EMPTY ); 
        
        //Buscamos la expresión regular al final de la cadena
        preg_match ( $regExp, $cadena, $ultimo, 0, $longitud ); 
        
        if ( empty ( $ultimo ) ) {
            //Si la última palabra no estaba seguida por un espacio en blanco,
            //la eliminamos del conjunto de palabras
            array_pop ( $palabras );
        }
        
        //Creamos la cadena resultante por la unión del conjunto de palabras
        $cadena = implode ( ' ', $palabras ) . '&hellip;';
    }
    
    return $cadena;
}





function cacheimg($img_url){

    if(file_exists("inc/cache_img/".basename($img_url))){

        $local_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/inc/cache_img/".basename($img_url);


    }else{


        if(file_put_contents("inc/cache_img/".basename($img_url), file_get_contents($img_url))){
            $local_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/inc/cache_img/".basename($img_url);
        }else{
            $local_url = $img_url;
        }
        


    }


    return $local_url;

}



function eliminar_acentos($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 

    $string = str_replace(
        array('Ő', 'Ẹ', 'Ă', 'Ǒ', 'Ũ', 'Ạ', 'Ộ', 'Ā', 'Ø', 'Ể','Ī','Ā','Ø','Ě','Ǐ'),
        array('O', 'E', 'A', 'O', 'U', 'A', 'O', 'A', 'O', 'E','I','A','O','E','I'),
        $string
    );

    $string = str_replace(
        array('ő', 'ẹ', 'ă', 'ǒ', 'ũ', 'ạ'),
        array('o', 'e', 'a', 'o', 'u', 'a'),
        $string
    );


    $string = str_replace(
        array('ộ', 'ā', 'ø', 'ể', 'ī', 'ā', 'ě', 'ǐ'),
        array('o', 'a', 'o', 'e', 'i', 'a', 'e', 'i'),
        $string
    );



    $string = str_replace(
        array('Ṽ', 'ḇ', 'ę', 'Ŝ', 'ę', 'ẋ', 'ụ', 'ş'),
        array('V', 'b', 'e', 'S', 'e', 'x', 'u', 's'),
        $string
    );

    $string = str_replace(
        array('ș','ǎ','ś','ḭ','ṙ','š','ṋ'),
        array('s', 'a', 's', 'i', 'r', 's', 'n'),
        $string
    );


    $string = str_replace(
        array('đ','ọ','ų','Ș','ų','ṩ'),
        array('d', 'o', 'u', 'S', 'u', 's'),
        $string
    );





    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    /*
    $string = str_replace(
        array("\", "¨", "º", "-", "~",
             "#", "@", "|", "!", """,
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );
 */
 
    return $string;
}



?>