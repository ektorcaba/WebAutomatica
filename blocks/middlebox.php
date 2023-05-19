<?php

    $banners = $db->query('SELECT * FROM banners WHERE position=2')->fetchAll();
        
    echo '<center>'.$banners[rand(0,(count($banners)-1))]['htmlcode'].'</center>';

?>