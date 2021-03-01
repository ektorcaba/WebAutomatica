<?php require "header.php"; ?>
        <main>



<h1><?= strtoupper(str_replace("-"," ",$_GET['q'])); ?></h1>
    <div class="cover">
        <?php

            $content_amazon = search_amazon_products($_GET['q'],1);


            //PRE CACHE IMAGES
            foreach($content_amazon as $product){
                cacheimg($product['image']);
            }

            foreach(array_slice($content_amazon,0,$settings['num_category_results']) as $product){
                echo '<div class="entry" onclick="window.open(\''.$product['link'].'\')">';
                //echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img src="'.cacheimg(str_replace("_AC_UL320_","_AC_AC_SR98,95_",$product['image'])).'" alt="'.$product['title'].'"></a></div>';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.eliminar_acentos($product['title']).'"></a></div>';

                //echo '<div class="iframebox">'; 

                    //echo'<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//rcm-eu.amazon-adsystem.com/e/cm?lt1=_blank&bc1=000000&IS2=1&bg1=FFFFFF&fc1=000000&lc1=0000FF&t='.$amz_tag.'&o=30&p=8&l=as4&m=amazon&f=ifr&ref=as_ss_li_til&asins='.$product['asin'].'"></iframe>';

                //echo'</div>';


                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimString($product['title'],85)).'</a></p>';

                if(!empty($product['price'])){
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                    //echo '<p><a href="'.$product['link'].'" rel="sponsored" target="_blank" class="btn">COMPRAR '.$product['price'].'</a></p></div>';
                }else{
                    //echo '<p><a href="'.$product['link'].'" rel="sponsored" target="_blank" class="btn">MAS INFORMACION</a></p></div>';
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">consultar</a></div></div>';    
                }
                //echo '<p><a href="'.$product['link'].'" rel="sponsored" target="_blank" class="btn">MAS INFORMACION</a></p></div>';

            }


        ?>



    </div>
    <p align="center"><a href="https://www.amazon.es/s?k=<?= strtoupper(str_replace("-"," ",$_GET['q'])); ?>&tag=<?= $amz_tag; ?>" rel="sponsored" target="_blank" class="btn">VER MAS</a></p>
    <div class="spacer">&nbsp;</div>
        <div class="middlebox"><?php include "blocks/middlebox.php"; ?></div>
    <div class="spacer">&nbsp;</div>

        </main>
<?php include "footer.php"; ?>