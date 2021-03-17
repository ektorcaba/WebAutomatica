<?php



set_time_limit(0);
error_reporting(E_ERROR);


include("settings.php");



$db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);



$st = $db->prepare('SELECT * FROM domains WHERE id=1');
$st->execute();

$domain = $st->fetch(PDO::FETCH_ASSOC);

$settings['domain_id'] = $domain['id'];
$settings['host'] = $domain['domain'];

$dominio = $domain['domain'];


$settings['menu'] = $db->query('SELECT * FROM menu WHERE domain_id='.$domain['id'].' ORDER BY position ASC');

foreach($db->query('SELECT * FROM settings WHERE domain_id='.$domain['id']) as $entry){

    $settings[$entry['key']] = $entry['value'];

}

$amz_tag = $settings['amazon_tag'];
$api_key = $settings['apikey'];
$api_secret = $settings['apisecret'];
$cache_directory = $settings['cachedir']; 


download_data($settings['default_term'],1);




foreach($db->query("SELECT * FROM menu") as $menu){

    download_data($menu['url'],1);

}



$counter = 0;
$cantidad_a_publicar = mt_rand(11,27);

foreach($db->query("SELECT * FROM keywords WHERE indexed IS NULL") as $keyword){

    if($counter<$cantidad_a_publicar){
        download_data_keyword($keyword);
        $counter++;
        //simula tiempo 18-52 minutos entre publicaciones
        sleep(1080,3120);
    }else{
        //espera al dia siguiente
        sleep(mt_rand(50400,86400));
        $counter = 0;
        $cantidad_a_publicar = mt_rand(11,27);
        download_data_keyword($keyword);
    }


}















function get_amazon_url($keyword, $sleep = 5){

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_VERBOSE => false,
        CURLOPT_FRESH_CONNECT => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 1500,
        CURLOPT_ENCODING => '', // Warning: if we don't say "Accept-Encoding: gzip", the SOB's at Amazon will send it gzip-compressed anyway.
        CURLOPT_URL => 'https://www.amazon.es/s?k='.str_replace("-","+",str_replace(" ","+",urlencode(trim($keyword))))
    ));
    
    $content = curl_exec($ch);
    curl_close($ch);
    $word = "Teclea los caracteres que aparecen en la imagen";

    //print 'https://www.amazon.es/s?k='.str_replace("-","+",str_replace(" ","+",urlencode(trim(str_replace("+"," ",$keyword)))))."\n";


    if($content !== false) {

        if(strpos($content, $word) !== false){

            sleep($sleep);
            get_amazon_url($keyword,$sleep+3);
        }else{
            if(empty($content)){

                sleep($sleep);
                get_amazon_url($keyword,$sleep+3);
            }else{
                if(strlen($content)>20000){
                    return $content;
                }else{

                    sleep($sleep);
                    get_amazon_url($keyword,$sleep+3);                
                }
                
            }
            
        }
    }else{

        sleep($sleep);
        get_amazon_url($keyword,$sleep+3); 
    }
    

}














function download_data($palabra_clave,$is_menu=0){
global $cache_directory, $db;

    $p_orig = $palabra_clave;

    if($is_menu == 1){
        $palabra_clave = str_replace("-"," ", $palabra_clave);
    }
    
    if(( !file_exists($cache_directory.sha1($palabra_clave).".html")) AND ( !file_exists($cache_directory.sha1($palabra_clave).".json")))
    {



    $html = get_amazon_url($palabra_clave);



    @($domd = new DOMDocument())->loadHTML($html);
    $xp=new DOMXPath($domd);

    $xquery = '//div[@data-component-type="s-search-result"]';           
    $links = $xp->query($xquery);






    //CHEKEA KE TENGA CONTENIDO EL RESULTADO DE AMAZON
    foreach($links as $l){





        if(@(trim($xp->query('.//span[@class="s-label-popover-default"]',$l)->item(0)->textContent)) != "Patrocinado"){


            if($l->getAttribute("data-asin")){
                echo "EXISTE - ";
                echo $l->getAttribute("data-asin");
                echo "\n";

                $existe = 1;



                break;
            }else{

                $existe = 0;
            }

        }

    }


    if($existe==1){




        $d = $domd->saveHTML();




        file_put_contents($cache_directory.sha1($palabra_clave).".html", $d);



        if(filesize($cache_directory.sha1($palabra_clave).".html") < 10000){
 
            unlink($cache_directory.sha1($palabra_clave).".html");
            sleep(10);
            download_data($palabra_clave);
        }

    }



    sleep(rand(5,8));

}else{

    print "EXISTE ARCHIVO!\n";

}



}










function download_data_keyword($palabra_clave_array,$is_menu=0){
global $cache_directory, $db;


$palabra_clave = $palabra_clave_array['amazon_term'];

$porig = $palabra_clave_array['slug'];


    
if(( !file_exists($cache_directory.sha1(str_replace("-"," ", $porig)).".html")) AND ( !file_exists($cache_directory.sha1(str_replace("-"," ", $porig)).".json"))){



    $html = get_amazon_url($palabra_clave);


    @($domd = new DOMDocument())->loadHTML($html);
    $xp=new DOMXPath($domd);

    $xquery = '//div[@data-component-type="s-search-result"]';           
    $links = $xp->query($xquery);

    //CHEKEA KE TENGA CONTENIDO EL RESULTADO DE AMAZON
    foreach($links as $l){
        if(@(trim($xp->query('.//span[@class="s-label-popover-default"]',$l)->item(0)->textContent)) != "Patrocinado"){
            if($l->getAttribute("data-asin")){
                echo "EXISTE - ";
                echo $l->getAttribute("data-asin");
                echo "\n";

                $existe = 1;

                $db->query("UPDATE keywords SET indexed=1, cache_type='html', fecha=NOW() WHERE id=".$palabra_clave_array['id']);

                break;
            }else{
                //echo "NOOOOOO - ";
                $existe = 0;
            }

        }

    }


    if($existe==1){

        $d = $domd->saveHTML();
        file_put_contents($cache_directory.sha1(str_replace("-"," ", $palabra_clave_array['slug'])).".html", $d);

        if(filesize($cache_directory.sha1(str_replace("-"," ", $palabra_clave_array['slug'])).".html") < 10000){
            unlink($cache_directory.sha1(str_replace("-"," ", $palabra_clave_array['slug'])).".html");
            sleep(10);
            download_data_keyword($palabra_clave_array);
        }

    }



    sleep(rand(5,8));

}else{


    $dats = $db->query('SELECT * FROM keywords WHERE id='.$palabra_clave_array['id'])->fetch();

    if($dats['indexed'] != 1){
        $db->query("UPDATE keywords SET indexed=1, cache_type='html', fecha=NOW() WHERE id=".$palabra_clave_array['id']);
    }
    


}



}
















?>