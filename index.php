<?php require "header.php"; ?>

        <main>


    <div class="cover">
        <?php

            if(!isset($_GET['k'])){
                $key = $settings['default_term'];
            }else{
                $key = $_GET['k'];
            }


            $content_amazon = search_amazon_products($key,1);


            if(empty($content_amazon)){

                $content_amazon = search_rand_amazon_products(0);

            }


            //PRE CACHE IMAGES
            foreach($content_amazon as $product){
                cacheimg($product['image']);
            }



            foreach(array_slice($content_amazon,0,$settings['num_results']) as $product){



                echo '<div class="entry" onclick="window.open(\''.$product['link'].'\')">';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.eliminar_acentos($product['title']).'"></a></div>';


                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimstring($product['title'],85)).'</a></p>';
                if(!empty($product['price'])){
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                }else{

                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">consultar</a></div></div>'; 
                }


            }


        ?>



    </div>

    <div class="spacer">&nbsp;</div>
        <div class="middlebox"><?php include "blocks/middlebox.php"; ?></div>
    <div class="spacer">&nbsp;</div>
        <?php


        if(!empty($data)){


            print '<article>';
            foreach($data as $entry){



                
                if(!empty($entry['spintitle'])){
                    print '<h1>'.mb_strtoupper(utf8_encode($entry['spintitle']),'utf-8').'</h1>';
                }

                echo htmlspecialchars_decode(utf8_encode($entry['spintext']));
                

            }
            print '</article>';
        }

        if(empty($_GET['k'])){

            echo "<h1>".utf8_encode($settings['slogan'])."</h1>";
            
            print '<article>';

            echo htmlspecialchars_decode(utf8_encode($settings['main_text']));
            print '</article>';

        }


        ?>


    <div class="spacer">&nbsp;</div>


    <h2>ARTICULOS RELACIONADOS</h2>
    <div class="cover">
        
        <?php

            if(!isset($_GET['k'])){
                $key = $settings['default_term'];
            }else{
                $key = $_GET['k'];
            }




            foreach(array_slice($content_amazon,$settings['num_results'],$settings['num_related_results']) as $product){
                echo '<div class="entry" onclick="window.open(\''.$product['link'].'\')">';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.eliminar_acentos($product['title']).'"></a></div>';


                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimString($product['title'],75)).'</a></p>';

                if(!empty($product['price'])){
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                }else{

                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">consultar</a></div></div>';  
                }


            }


        ?>



    </div>
        </main>
    
<?php include "footer.php"; ?>