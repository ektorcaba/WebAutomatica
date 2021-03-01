<?php require "functions.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php


        $strings = array(
            '⇒',
            '▷',
            '🥇',
            '✅',
            '🔥',
            '👍',
            '⭐'
        );
        $dot_seo = array_rand($strings);


    if(empty($_GET['k']) AND empty($_GET['q'])){
        echo $strings[$dot_seo]." ".$settings['sitename']." - ".$settings['slogan'];
    }else{
        if(empty($_GET['k'])){
            echo $strings[$dot_seo]." ".mb_strtoupper(str_replace("-"," ",$_GET['q']),'utf-8')." - ".$settings['sitename'];
        }else{
            echo $strings[$dot_seo]." ".mb_strtoupper(str_replace("-"," ",$_GET['k']),'utf-8')." - ".$settings['sitename'];
        }
    }
    
    
    ?></title>
    <meta name="description" content="<?=utf8_encode($settings['description']);?>" />
    <style>
        :root {
            --linkcolor: <?= $settings['link_color'];?>;
            --maincolor: <?= $settings['main_color'];?>;
            --secondarycolor: <?= $settings['secondary_color'];?>;
            --maintextcolor: <?= $settings['maintext_color'];?>;
            --secondarytextcolor: <?= $settings['secondarytext_color'];?>;
        }
    </style>

    <link rel="stylesheet" href="/assets/style_<?= $estilo_menu; ?>.css">
    <link rel="icon" type="image/png" href="/assets/favicon.png">


</head>
<body>
<div class="mainwrap">
<div class="leftbox"><?php include "blocks/leftbox.php"; ?></div>
        <div class="wrap">
        <header>
            <a href="/"><img class="logo" src="<?=$settings['logo'];?>" alt="<?= $settings['sitename'];?> - <?= $settings['slogan'];?>"></a>
            <?php

                if($estilo_menu ==="vertical"){
                    echo '</header>';
                }


            ?>
            <button type="button" class="collapsible">Menu</button>
            <div class="topnav">
            <?php
                foreach($settings['menu'] as $nav){
                    echo '<a href="/categoria/'.str_replace(" ","-",strtolower($nav['url'])).'">'.$nav['text'].'</a>';
                }
            ?>
            <form action="https://www.google.es/search" target="_blank" id="gform">
                <input type="text" placeholder="Buscar.." name="q" id="q" autocomplete="off">
                <input type="hidden" name="q" value="site:<?= $_SERVER['HTTP_HOST']; ?>">
            </form>
            </div>
            <?php

                //if($estilo_menu ==="horizontal"){
                    echo '</header>';
                //}


            ?>
            
         