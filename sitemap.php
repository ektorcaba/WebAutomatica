<?php

require "inc/settings.php";



header('Content-type: text/xml');



   echo '<?xml version="1.0" encoding="UTF-8"?>'
   .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
   echo '<url><loc>'.$actual_link.'</loc></url>';

   foreach($db->query("SELECT * FROM menu")->fetchAll() as $link){

      echo '<url><loc>'.$actual_link."/categoria/".str_replace(" ","-",strtolower($link['url'])).'</loc><lastmod>'.date('Y-m-d').'</lastmod><changefreq>daily</changefreq></url>';
	   
   }


   foreach($db->query("SELECT DISTINCT(slug) FROM keywords WHERE indexed=1")->fetchAll() as $link){

      echo '<url><loc>'.$actual_link.'/'.$link['slug'].'</loc><lastmod>'.date('Y-m-d').'</lastmod><changefreq>daily</changefreq></url>';


   }


   echo '</urlset>';

?>