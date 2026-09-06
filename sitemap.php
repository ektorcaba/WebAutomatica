<?php

require "inc/settings.php";


// Consulta para obtener la fecha máxima formateada como YYYY-MM-DD
    $stmt = $db->query("SELECT DATE_FORMAT(MAX(fecha), '%Y-%m-%d') AS ultima_fecha FROM keywords");
    
    // Obtener el valor directo
    $fecha_lastmod = $stmt->fetchColumn();


header('Content-type: text/xml');



   echo '<?xml version="1.0" encoding="UTF-8"?>'
   .'<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">';

   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
   echo '<url><loc>'.$actual_link.'</loc><lastmod>'.$fecha_lastmod.'</lastmod></url>';

   foreach($db->query("SELECT * FROM menu")->fetchAll() as $link){

      //echo '<url><loc>'.$actual_link."/categoria/".str_replace(" ","-",strtolower($link['url'])).'</loc><lastmod>'.date('Y-m-d').'</lastmod><changefreq>daily</changefreq></url>';
      echo '<url><loc>'.$actual_link."/categoria/".str_replace(" ","-",strtolower($link['url'])).'</loc><lastmod>'.$fecha_lastmod.'</lastmod></url>';
	   
   }


   foreach($db->query("SELECT DISTINCT(slug) FROM keywords")->fetchAll() as $link){
   //foreach($db->query("SELECT DISTINCT(slug) FROM keywords WHERE indexed=1")->fetchAll() as $link){
      //echo '<url><loc>'.$actual_link.'/'.$link['slug'].'</loc><lastmod>'.date('Y-m-d').'</lastmod><changefreq>daily</changefreq></url>';
      echo '<url><loc>'.$actual_link.'/'.$link['slug'].'</loc><lastmod>'.$fecha_lastmod.'</lastmod></url>';


   }


   echo '</urlset>';

?>
