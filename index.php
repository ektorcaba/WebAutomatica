<?php require "header.php"; ?>
<?php



if(!isset($_GET['k'])){
    $key = $settings['default_term'];
}else{
    $key = $_GET['k'];
}


$content_amazon = search_amazon_products($key,1);


if(empty($content_amazon)){

    $content_amazon = search_rand_amazon_products($key);

}



$i = 0;

foreach(array_slice($content_amazon,0,$settings['num_results']) as $product){

    if($i == 0){
        echo'<div itemtype="https://schema.org/Product" itemscope>
        <meta itemprop="mpn" content="'.$product['asin'].'" />
        <meta itemprop="name" content="'.eliminar_acentos(trimstring($product['title'],50)).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <meta itemprop="description" content="'.eliminar_acentos(trimstring($product['title'],100)).'" />
        <div itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating" itemscope>
            <meta itemprop="reviewCount" content="'.rand(rand(3,6),rand(12,35)).'" />
            <meta itemprop="ratingValue" content="4.'.rand(4,8).'" />
        </div>
        <div itemprop="review" itemtype="https://schema.org/Review" itemscope>
            <div itemprop="author" itemtype="https://schema.org/Person" itemscope>
            <meta itemprop="name" content="'.$settings['sitename'].'" />
            </div>
            <div itemprop="reviewRating" itemtype="https://schema.org/Rating" itemscope>
                <meta itemprop="ratingValue" content="'.rand(4,5).'" />
                <meta itemprop="bestRating" content="5" />
            </div>
        </div>
        <div itemprop="offers" itemtype="https://schema.org/AggregateOffer" itemscope>
          <link itemprop="url" href="'.$product['link'].'" />
          <meta itemprop="availability" content="https://schema.org/InStock" />
          <meta itemprop="priceCurrency" content="EUR" />
          <meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
          <meta itemprop="lowPrice" content="1.00" />
          <meta itemprop="offerCount" content="'.rand(rand(3,6),rand(12,35)).'" />
          <meta itemprop="highPrice" content="';
          
          if(!empty($product['price'])){
            echo number_format(str_replace("€","",str_replace(" ","",$product['price'])),2);
          }else{
            echo "1.50";
          }

        echo '" />
        </div>
        <meta itemprop="sku" content="'.$product['asin'].'" />
        <div itemprop="brand" itemtype="https://schema.org/Brand" itemscope>
          <meta itemprop="name" content="AMAZON" />
        </div>
      </div>';

        $i++;
    }
}

?>

        <main>



    <div class="cover">
        <?php



            //PRE CACHE IMAGES
            foreach($content_amazon as $product){
                cacheimg($product['image']);
            }



            foreach(array_slice($content_amazon,0,$settings['num_results']) as $product){


                echo '<div class="entry">';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.($product['title']).'"></a></div>';


                //echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimstring($product['title'],85)).'</a></p>';
                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.(($product['title'])).'</a></p>';

                if(!empty($product['price'])){
                    //echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                    echo '<div class="onbottom"></div></div>';
                }else{

                    echo '<div class="onbottom"></div></div>'; 
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

            echo "<h1>".($settings['slogan'])."</h1>";
            
            print '<article>';

            echo htmlspecialchars_decode(utf8_encode($settings['main_text']));
            print '</article>';

        }


        ?>


    <div class="spacer">&nbsp;</div>

    <?php

        if(!empty(array_slice($content_amazon,$settings['num_results'],$settings['num_related_results']) )){
            echo "<h2>ARTICULOS RELACIONADOS</h2>";
        }


    ?>
    <div class="cover">
        
        <?php

            if(!isset($_GET['k'])){
                $key = $settings['default_term'];
            }else{
                $key = $_GET['k'];
            }



            foreach(array_slice($content_amazon,$settings['num_results'],$settings['num_related_results']) as $product){
                echo '<div class="entry">';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.($product['title']).'"></a></div>';


                //echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimString($product['title'],75)).'</a></p>';
                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.(($product['title'])).'</a></p>';

                if(!empty($product['price'])){
                    //echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                    echo '<div class="onbottom"></div></div>';
                }else{

                    echo '<div class="onbottom"></div></div>';  
                }


            }


        ?>



    </div>
        </main>
    
<?php include "footer.php"; ?>