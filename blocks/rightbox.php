<?php

    $banners = $db->query('SELECT * FROM banners WHERE position=3')->fetchAll();
        
    echo $banners[rand(0,(count($banners)-1))]['htmlcode'];

?>