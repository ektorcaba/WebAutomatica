<?php

set_time_limit(0);
error_reporting(E_ERROR);

include("settings.php");

$db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);

$st = $db->prepare('SELECT * FROM domains WHERE id=1');
$st->execute();

$domain = $st->fetch(PDO::FETCH_ASSOC);

if ($domain) {
    $settings['domain_id'] = $domain['id'];
    $settings['host'] = $domain['domain'];
    $dominio = $domain['domain'];

    $settings['menu'] = $db->query('SELECT * FROM menu WHERE domain_id='.$domain['id'].' ORDER BY position ASC');

    foreach($db->query('SELECT * FROM settings WHERE domain_id='.$domain['id']) as $entry){
        $settings[$entry['key']] = $entry['value'];
    }
}

$amz_tag = $settings['amazon_tag'] ?? '';
$api_key = $settings['apikey'] ?? '';
$api_secret = $settings['apisecret'] ?? '';
$cache_directory = $settings['cachedir'] ?? ''; 

if (isset($settings['default_term'])) {
    download_data($settings['default_term'], 1);
}

foreach($db->query("SELECT * FROM menu") as $menu){
    download_data($menu['url'], 1);
}

$counter = 0;
$cantidad_a_publicar = mt_rand(11, 27);

foreach($db->query("SELECT * FROM keywords WHERE indexed IS NULL") as $keyword){

    if($counter < $cantidad_a_publicar){
        download_data_keyword($keyword);
        $counter++;
        // Simula tiempo 18-52 minutos entre publicaciones
        sleep(mt_rand(1080, 3120));
    } else {
        // Espera al día siguiente
        sleep(mt_rand(50400, 86400));
        $counter = 0;
        $cantidad_a_publicar = mt_rand(11, 27);
        download_data_keyword($keyword);
    }
}

function get_amazon_url($keyword, $sleep = 5){
    // Límite de reintentos para evitar recursión infinita
    if ($sleep > 30) {
        return false;
    }

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
        CURLOPT_ENCODING => '',
        CURLOPT_URL => 'https://www.amazon.es/s?k='.str_replace("-","+",str_replace(" ","+",urlencode(trim((string)$keyword))))
    ));
    
    $content = curl_exec($ch);
    curl_close($ch);
    $word = "Teclea los caracteres que aparecen en la imagen";

    if($content !== false && !empty($content)) {
        if(strpos($content, $word) !== false || strlen($content) <= 20000){
            sleep($sleep);
            return get_amazon_url($keyword, $sleep + 3);
        } else {
            return $content;
        }
    } else {
        sleep($sleep);
        return get_amazon_url($keyword, $sleep + 3); 
    }
}

function download_data($palabra_clave, $is_menu = 0){
    global $cache_directory, $db;

    if($is_menu == 1){
        $palabra_clave = str_replace("-", " ", $palabra_clave);
    }
    
    $file_html = $cache_directory . sha1($palabra_clave) . ".html";

    if(!file_exists($file_html)) {

        $html = get_amazon_url($palabra_clave);
        if (!$html) return;

        libxml_use_internal_errors(true);
        $domd = new DOMDocument();
        // Forzamos codificación UTF-8 al cargar HTML para evitar problemas de acentos
        $domd->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xp = new DOMXPath($domd);
        // Buscamos los bloques de resultados por su atributo data-component-type
        $xquery = '//div[@data-component-type="s-search-result"]';           
        $links = $xp->query($xquery);

        $existe = 0;

        if ($links) {
            foreach($links as $l){
                // Descartar patrocinados revisando la presencia de sus clases habituales
                $sponsoredNode = $xp->query('.//*[contains(@class, "s-sponsored-label-info-icon") or contains(@class, "puis-sponsored-label-text")]', $l);

                if ($sponsoredNode->length === 0) {
                    $asin = $l->getAttribute("data-asin");
                    if (!empty($asin)) {
                        echo "EXISTE - " . $asin . "\n";
                        $existe = 1;
                        break;
                    }
                }
            }
        }

        if($existe == 1){
            $d = $domd->saveHTML();
            file_put_contents($file_html, $d);

            if(filesize($file_html) < 10000){
                unlink($file_html);
                sleep(10);
                download_data($palabra_clave, $is_menu);
            }
        }

        sleep(rand(5,8));
    } else {
        print "EXISTE ARCHIVO!\n";
    }
}

function download_data_keyword($palabra_clave_array, $is_menu = 0){
    global $cache_directory, $db;

    $palabra_clave = $palabra_clave_array['amazon_term'];
    $porig = $palabra_clave_array['slug'];

    $file_html = $cache_directory . sha1(str_replace("-", " ", $porig)) . ".html";

    if(!file_exists($file_html)){

        $html = get_amazon_url($palabra_clave);
        if (!$html) return;

        libxml_use_internal_errors(true);
        $domd = new DOMDocument();
        $domd->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xp = new DOMXPath($domd);
        $xquery = '//div[@data-component-type="s-search-result"]';           
        $links = $xp->query($xquery);

        $existe = 0;

        if ($links) {
            foreach($links as $l){
                $sponsoredNode = $xp->query('.//*[contains(@class, "s-sponsored-label-info-icon") or contains(@class, "puis-sponsored-label-text")]', $l);

                if ($sponsoredNode->length === 0) {
                    $asin = $l->getAttribute("data-asin");
                    if (!empty($asin)) {
                        echo "EXISTE - " . $asin . "\n";
                        $existe = 1;

                        $st = $db->prepare("UPDATE keywords SET indexed=1, cache_type='html', fecha=NOW() WHERE id = ?");
                        $st->execute([$palabra_clave_array['id']]);

                        break;
                    }
                }
            }
        }

        if($existe == 1){
            $d = $domd->saveHTML();
            file_put_contents($file_html, $d);

            if(filesize($file_html) < 10000){
                unlink($file_html);
                sleep(10);
                download_data_keyword($palabra_clave_array);
            }
        }

        sleep(rand(5,8));

    } else {
        $st = $db->prepare('SELECT * FROM keywords WHERE id = ?');
        $st->execute([$palabra_clave_array['id']]);
        $dats = $st->fetch();

        if($dats && $dats['indexed'] != 1){
            $stUpd = $db->prepare("UPDATE keywords SET indexed=1, cache_type='html', fecha=NOW() WHERE id = ?");
            $stUpd->execute([$palabra_clave_array['id']]);
        }
    }
}
