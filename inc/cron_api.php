<?php



set_time_limit(0);
error_reporting(E_ERROR);

include("settings.php");
require("AwsV4.php");






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
    }else{
        //espera al dia siguiente
        sleep(mt_rand(50400,86400));
        $counter = 0;
        $cantidad_a_publicar = mt_rand(11,27);
        download_data_keyword($keyword);
    }
    

}





function get_amazon_url_default($keyword, $sleep = 5){
    global $amz_tag, $db, $settings;


    $data = [];


    for($i=1;$i<7;$i++){

        $fetch = json_decode(getContentApi($keyword, $amz_tag, $i));

    
        
      foreach($fetch->SearchResult->Items as $it){
        $data[] = $it;
      }

      sleep(rand(2,5));

    }


    if(count($data)>1){

        $data_obj = (object) ['SearchResult' => ['Items' => $data]];

        return json_encode($data_obj);       
    }else{

        if(file_exists($cache_directory.sha1(str_replace("-"," ", $settings['default_term'])).".json")){
            return file_get_contents($cache_directory.sha1(str_replace("-"," ", $settings['default_term'])).".json");
        }


    }



}









function get_amazon_url($keyword, $sleep = 5){
    global $amz_tag, $db;


    $dats = $db->query('SELECT * FROM keywords WHERE slug="'.$keyword.'"')->fetch();

    $data = [];


    for($i=1;$i<7;$i++){

        $fetch = json_decode(getContentApi($dats['amazon_term'], $amz_tag, $i));

      foreach($fetch->SearchResult->Items as $it){
        $data[] = $it;
      }

      sleep(rand(2,5));

    }


    if(count($data)>1){

        $data_obj = (object) ['SearchResult' => ['Items' => $data]];

        return json_encode($data_obj);       
    }else{


        if(file_exists($cache_directory.sha1(str_replace("-"," ", $settings['default_term'])).".json")){
            return file_get_contents($cache_directory.sha1(str_replace("-"," ", $settings['default_term'])).".json");
        }

    }



}










function download_data($palabra_clave,$is_menu=0){
global $cache_directory, $db;

    $porig = $palabra_clave;

    if($is_menu == 1){
        $palabra_clave = str_replace("-"," ", $palabra_clave);
    }



    
    if(( !file_exists($cache_directory.sha1($palabra_clave).".html")) AND ( !file_exists($cache_directory.sha1($palabra_clave).".json")))
    {


        $html = get_amazon_url_default($palabra_clave);




        if($html){


            file_put_contents($cache_directory.sha1($palabra_clave).".json", $html);

        }


        sleep(rand(2,5));

    }else{

        //print "EXISTE ARCHIVO!\n";


    }


}










function download_data_keyword($palabra_clave_array,$is_menu=0){
global $cache_directory, $db;


$porig = $palabra_clave_array['slug'];


$palabra_clave = str_replace("-"," ", $porig);


    
if(( !file_exists($cache_directory.sha1($palabra_clave).".html")) AND ( !file_exists($cache_directory.sha1($palabra_clave).".json"))){



        $html = get_amazon_url($porig);


        if($html){

            file_put_contents($cache_directory.sha1($palabra_clave).".json", $html);

        }


        $dats = $db->query('SELECT * FROM keywords WHERE id='.$palabra_clave_array['id'])->fetch();

        if($dats['indexed'] != 1){
            $db->query("UPDATE keywords SET indexed=1, cache_type='json' WHERE id=".$palabra_clave_array['id']);
        }


        sleep(rand(2,5));



}else{


    $dats = $db->query('SELECT * FROM keywords WHERE id='.$palabra_clave_array['id'])->fetch();

    if($dats['indexed'] != 1){
        $db->query("UPDATE keywords SET indexed=1, cache_type='json' WHERE id=".$palabra_clave_array['id']);
    }
    


}



}













function getContentApi($keyword, $tag, $page = 1){
    global $api_key, $api_secret;

    $searchItemRequest = new SearchItemsRequest ();
    $searchItemRequest->PartnerType = "Associates";
    // Put your Partner tag (Store/Tracking id) in place of Partner tag
    $searchItemRequest->PartnerTag = $tag;
    $searchItemRequest->Keywords = $keyword;
    $searchItemRequest->SearchIndex = "All";
    $searchItemRequest->ItemCount = 10;
    $searchItemRequest->ItemPage = $page;
    $searchItemRequest->Resources = ["Images.Primary.Large","ItemInfo.Title","Offers.Listings.Price"];
    $host = "webservices.amazon.es";
    $path = "/paapi5/searchitems";
    $payload = json_encode ($searchItemRequest);
    //Put your Access Key in place of <ACCESS_KEY> and Secret Key in place of <SECRET_KEY> in double quotes
    $awsv4 = new AwsV4 ($api_key, $api_secret);
    $awsv4->setRegionName("eu-west-1");
    $awsv4->setServiceName("ProductAdvertisingAPI");
    $awsv4->setPath ($path);
    $awsv4->setPayload ($payload);
    $awsv4->setRequestMethod ("POST");
    $awsv4->addHeader ('content-encoding', 'amz-1.0');
    $awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
    $awsv4->addHeader ('host', $host);
    $awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.SearchItems');
    $headers = $awsv4->getHeaders ();
    $headerString = "";
    foreach ( $headers as $key => $value ) {
        $headerString .= $key . ': ' . $value . "\r\n";
    }
    $params = array (
            'http' => array (
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload
            )
        );
    $stream = stream_context_create ( $params );

    $fp = @fopen ( 'https://'.$host.$path, 'rb', false, $stream );

    if (! $fp) {
        //throw new Exception ( "Exception Occured".$fp );
        sleep(3);
        getContentApi($keyword, $tag, $page);
    }
    $response = @stream_get_contents ( $fp );
    if ($response === false) {
        //throw new Exception ( "Exception Occured" );
        sleep(3);
        getContentApi($keyword, $tag, $page);

    }

    return $response;

}





class SearchItemsRequest {
    public $PartnerType;
    public $PartnerTag;
    public $Keywords;
    public $SearchIndex;
    public $Resources;
}





?>