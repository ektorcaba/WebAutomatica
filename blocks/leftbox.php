<?php

    $banners = $db->query('SELECT * FROM banners WHERE position=1')->fetchAll();
        
    echo $banners[rand(0,(count($banners)-1))]['htmlcode'];

?>