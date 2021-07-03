<?php require "header.php"; ?>


<?php



$content_amazon = search_amazon_products($_GET['q'],1);


//PRE CACHE IMAGES
foreach($content_amazon as $product){
    cacheimg($product['image']);
}



$i = 0;

foreach(array_slice($content_amazon,0,$settings['num_category_results']) as $product){



    if($i == 0){
        echo'<div itemtype="http://schema.org/Product" itemscope>
        <meta itemprop="mpn" content="'.$product['asin'].'" />
        <meta itemprop="name" content="'.eliminar_acentos(trimstring($product['title'],50)).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <link itemprop="image" href="'.cacheimg($product['image']).'" />
        <meta itemprop="description" content="'.eliminar_acentos(trimstring($product['title'],100)).'" />
        <div itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>
        <meta itemprop="reviewCount" content="'.rand(rand(3,6),rand(12,35)).'" />
        <meta itemprop="ratingValue" content="4.'.rand(4,8).'" />
      </div>
      <div itemprop="review" itemtype="http://schema.org/Review" itemscope>
      <div itemprop="author" itemtype="http://schema.org/Person" itemscope>
      <meta itemprop="name" content="'.$settings['sitename'].'" />
      </div>
      <div itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
          <meta itemprop="ratingValue" content="'.rand(4,5).'" />
          <meta itemprop="bestRating" content="5" />
      </div>
  </div>
        <div itemprop="offers" itemtype="http://schema.org/AggregateOffer" itemscope>
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
        <div itemprop="brand" itemtype="http://schema.org/Brand" itemscope>
          <meta itemprop="name" content="AMAZON" />
        </div>
      </div>';

        $i++;
    }
}

?>



        <main>



<h1><?= strtoupper(str_replace("-"," ",$_GET['q'])); ?></h1>
    <div class="cover">
        <?php



            foreach(array_slice($content_amazon,0,$settings['num_category_results']) as $product){
                echo '<div class="entry" onclick="window.open(\''.$product['link'].'\')">';
                //echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img src="'.cacheimg(str_replace("_AC_UL320_","_AC_AC_SR98,95_",$product['image'])).'" alt="'.$product['title'].'"></a></div>';

                echo '<div class="imgbox"><a href="'.$product['link'].'" rel="sponsored" target="_blank"><img loading="lazy" src="'.cacheimg($product['image']).'" alt="'.($product['title']).'"></a></div>';

                //echo '<div class="iframebox">'; 

                    //echo'<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//rcm-eu.amazon-adsystem.com/e/cm?lt1=_blank&bc1=000000&IS2=1&bg1=FFFFFF&fc1=000000&lc1=0000FF&t='.$amz_tag.'&o=30&p=8&l=as4&m=amazon&f=ifr&ref=as_ss_li_til&asins='.$product['asin'].'"></iframe>';

                //echo'</div>';


                //echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.eliminar_acentos(trimString($product['title'],85)).'</a></p>';
                echo '<p class="title"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.(($product['title'])).'</a></p>';

                if(!empty($product['price'])){
                    //echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">'.$product['price'].'</a></div></div>';
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank"></a></div></div>';
                    //echo '<p><a href="'.$product['link'].'" rel="sponsored" target="_blank" class="btn">COMPRAR '.$product['price'].'</a></p></div>';
                }else{
                    //echo '<p><a href="'.$product['link'].'" rel="sponsored" target="_blank" class="btn">MAS INFORMACION</a></p></div>';
                    //echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank">consultar</a></div></div>';  
                    echo '<div class="onbottom"><a href="'.$product['link'].'" rel="sponsored" target="_blank"></a></div></div>';  
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