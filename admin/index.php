<?php

//require_auth("admin","opalucas2021!$");





if(!file_exists('../inc/settings.php')){

    if($_POST){
        $data = '<?php


        $dbname = "'.$_POST['dbname'].'";
        $dbuser = "'.$_POST['dbuser'].'";
        $dbpass = "'.$_POST['dbpass'].'";
        $dbhost = "'.$_POST['dbhost'].'";
        
        
        
        
        $db = new PDO(\'mysql:host=\'.$dbhost.\';dbname=\'.$dbname, $dbuser, $dbpass);
        
        
        
        
        ?>';


        if(file_put_contents("../inc/settings.php",$data)){

            require '../inc/settings.php';

            
            $db->query('CREATE TABLE `contents` (
                `id` int(11) NOT NULL,
                `keyword_id` int(11) NOT NULL,
                `spintitle` text NOT NULL,
                `spintext` longtext NOT NULL
              )');

            $db->query('CREATE TABLE `domains` (
                `id` int(11) NOT NULL,
                `domain` varchar(255) NOT NULL
            )');


            $db->query('CREATE TABLE `generated_spintext` (
            `id` int(11) NOT NULL,
            `content_id` int(11) NOT NULL,
            `spintitle` text NOT NULL,
            `spintext` text NOT NULL
            )');


            $db->query('CREATE TABLE `keywords` (
                `id` int(11) NOT NULL,
                `domain_id` int(11) NOT NULL,
                `parent_id` int(11) DEFAULT NULL,
                `amazon_term` varchar(255) NOT NULL,
                `keyword` varchar(255) NOT NULL,
                `slug` varchar(255) NOT NULL,
                `indexed` int(11) DEFAULT NULL,
                `cache_type` varchar(10) NOT NULL
            )');


            $db->query('CREATE TABLE `menu` (
                `id` int(11) NOT NULL,
                `domain_id` int(11) NOT NULL,
                `text` text NOT NULL,
                `url` text NOT NULL,
                `position` int(11) NOT NULL DEFAULT 0
            )');


            $db->query('CREATE TABLE `settings` (
                `id` int(11) NOT NULL,
                `domain_id` int(11) NOT NULL,
                `key` varchar(255) NOT NULL,
                `value` longtext NOT NULL
            )');



            $db->query("INSERT INTO `settings` (`id`, `domain_id`, `key`, `value`) VALUES (1, 1, 'sitename', 'Web'),(2, 1, 'slogan', 'Una web automatica'),(3, 1, 'main_color', '#ff5987'),(4, 1, 'secondary_color', '#ccc'),(5, 1, 'logo', '/assets/logo.svg'),(6, 1, 'main_text', '<h1>Portada en html</h1>'),(7, 1, 'default_term', 'palabra clave'),(8, 1, 'source_text', '{texto de prueba|texto principal}'),(9, 1, 'description', 'Descripcion SEO para google'),(10, 1, 'maintext_color', '#ffffff'),(11, 1, 'secondarytext_color', '#222'),(12, 1, 'link_color', '#222222'),(13, 1, 'style_menu', 'vertical'),(14, 1, 'num_category_results', '42'),(15, 1, 'num_results', '36'),(16, 1, 'num_related_results', '15'),(17, 1, 'amazon_tag', 'usuario.com.es-21'),(18, 1, 'apikey', 'AAAAAAAAAAAAAAAAAA'),(19, 1, 'apisecret', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),(20, 1, 'cachedir', 'cache/'),(21, 1, 'interlinking', '40'),(22, 1, 'google_tag', 'G-XXXXXXXXXX')");

            
            $db->query('ALTER TABLE `contents` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `domains` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `generated_spintext` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `keywords` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `menu` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `settings` ADD PRIMARY KEY (`id`)');
            $db->query('ALTER TABLE `contents` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
            $db->query('ALTER TABLE `domains` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
            $db->query('ALTER TABLE `generated_spintext` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
            $db->query('ALTER TABLE `keywords` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
            $db->query('ALTER TABLE `menu` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
            $db->query('ALTER TABLE `settings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22');
            $db->query('COMMIT');


        }











    }else{

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar web automatica</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</head>
<body>

    <div class="m-0 p-0 container-fluid">
        <div class="container mb-5">
        <div class="card mt-5">
            <div class="card-body">
                <h2 class="card-title" id="atext">BASE DE DATOS</h2>
                <form autocomplete="off" action="" method="post">
                            <div class="col-sm">
                                <label for="dbname">DB Name</label>
                                <input type="text" autocomplete="off" name="dbname" id="dbname" class="form-control form-control-lg">
                            </div>
                        <div class="row">
                            <div class="col-sm">
                                    <label for="dbhost">DB Host</label>
                                    <input type="text" autocomplete="off" name="dbhost" id="dbhost" value="localhost" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="dbuser">DB Username</label>
                                <input type="text" autocomplete="off" name="dbuser" id="dbuser" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="dbpass">DB Password</label>
                                <input type="password" autocomplete="off" name="dbpass" id="dbpass" class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-sm">
                                    <input type="submit" class="btn btn-primary btn-lg mt-3 d-block" value="CONTINUAR" />
                                    </br>
                                </div>
                            </div>           
                </form>
            </div>
        </div>
        </div>   
    </div>
</body>
</html>

<?php
die();
    }

}







require '../inc/settings.php';

$sets = $db->query("SELECT * FROM settings WHERE domain_id=1")->fetchAll();

foreach($sets as $s){
    $settings[$s['key']] = $s['value'];

}


if(isset($_GET['delmenu'])){

    $db->query("DELETE FROM menu WHERE id=".$_GET['delmenu']);
    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional
}


if(isset($_GET['delkey'])){

    $db->query("DELETE FROM keywords WHERE id=".$_GET['delkey']);
    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional
}




if(!empty($_POST['addtext'])){


    if(!empty($_POST['keyword_id'])){

        $sql = "INSERT INTO contents (id, keyword_id,spintitle,spintext) VALUES (NULL,'".$_POST['keyword_id']."','".$_POST['spintitle']."','".$_POST['spintext']."')";

        $db->query($sql);

    }



    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional

}











if(!empty($_POST['addmenu'])){

    if(!empty($_POST['menu'])){
        foreach(explode("\n",$_POST['menu']) as $t){
            $ts = explode(":", $t);
            $menu_temp[] = array('text'=> $ts[0], 'url' => $ts[1], 'position' => $ts[2]); 
        }
        //print_r($menu_temp);
    }


    foreach($menu_temp as $kt){
        $string = preg_replace("/[\r\n|\n|\r]+/", "", trim($kt['url']));

        $sql = "INSERT INTO menu (id, domain_id,text,url,position) VALUES (NULL,1,'".$kt['text']."','".$string."','".$kt['position']."')";

        $db->query($sql);

    }




    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional

}



if(!empty($_POST['addkeywords'])){
    if(!empty($_POST['keywords'])){
        foreach(explode("\n",$_POST['keywords']) as $t){
            $ts = explode(":", $t);
            if(count($ts)>1){

                //$result = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode(trim($ts[1]), ENT_QUOTES));
                $result = clean(html_entity_decode(trim($ts[1]), ENT_QUOTES));
                $keywords_temp[] = array('amazon_term' => $ts[0], 'keyword' => $result, 'slug' => clean(strtolower($result)));
            }else{

                //$result = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode(trim($ts[0]), ENT_QUOTES));
                $result = clean(html_entity_decode(trim($ts[0]), ENT_QUOTES));
                $keywords_temp[] = array('amazon_term' => $result, 'keyword' => $result, 'slug' => clean(strtolower($result)));                
            }
 

        }
    



        foreach($keywords_temp as $kt){

            $sql = "INSERT INTO keywords (id, domain_id,parent_id,amazon_term,keyword,slug) VALUES (NULL,1,NULL,'".$kt['amazon_term']."','".$kt['keyword']."','".$kt['slug']."')";

            $db->query($sql);

        }

    }


    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional

}





if(!empty($_POST['settings'])){


if(isset($_POST['setup'])){
    if($_POST['setup']==1){

        $sql = "INSERT INTO domains (id, domain) VALUES (1,'".$_POST['domain']."')";

        if($db->query($sql)) {
            echo "<a href='' class='btn'>Configurar ahora!</a>";
        } else {
            echo "Error: " . $sql . "<br>";
        }
        die();

    }
}





$upd_data[] = array(
    ':id' => 1,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'sitename',
    ':value' => $_POST['sitename'],
);

$upd_data[] = array(
    ':id' => 2,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'slogan',
    ':value' => $_POST['slogan'],
);


$upd_data[] = array(
    ':id' => 3,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'main_color',
    ':value' => $_POST['main_color'],
);


$upd_data[] = array(
    ':id' => 4,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'secondary_color',
    ':value' => $_POST['secondary_color'],
);


$upd_data[] = array(
    ':id' => 6,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'main_text',
    ':value' => $_POST['main_text'],
);



$upd_data[] = array(
    ':id' => 7,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'default_term',
    ':value' => $_POST['default_term'],
);


$upd_data[] = array(
    ':id' => 8,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'source_text',
    ':value' => $_POST['source_text'],
);


$upd_data[] = array(
    ':id' => 9,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'description',
    ':value' => $_POST['description'],
);


$upd_data[] = array(
    ':id' => 10,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'maintext_color',
    ':value' => $_POST['maintext_color'],
);

$upd_data[] = array(
    ':id' => 11,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'secondarytext_color',
    ':value' => $_POST['secondarytext_color'],
);

$upd_data[] = array(
    ':id' => 12,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'link_color',
    ':value' => $_POST['link_color'],
);

$upd_data[] = array(
    ':id' => 13,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'style_menu',
    ':value' => $_POST['style_menu'],
);

$upd_data[] = array(
    ':id' => 14,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'num_category_results',
    ':value' => $_POST['num_category_results'],
);

$upd_data[] = array(
    ':id' => 15,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'num_results',
    ':value' => $_POST['num_results'],
);


$upd_data[] = array(
    ':id' => 16,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'num_related_results',
    ':value' => $_POST['num_related_results'],
);

$upd_data[] = array(
    ':id' => 17,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'amazon_tag',
    ':value' => $_POST['amazon_tag'],
);

$upd_data[] = array(
    ':id' => 18,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'apikey',
    ':value' => $_POST['apikey'],
);

$upd_data[] = array(
    ':id' => 19,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'apisecret',
    ':value' => $_POST['apisecret'],
);

$upd_data[] = array(
    ':id' => 20,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'cachedir',
    ':value' => $_POST['cachedir'],
);

$upd_data[] = array(
    ':id' => 21,
    ':domain_id' => $_POST['domain_id'],
    ':key' => 'interlinking',
    ':value' => $_POST['interlinking'],
);



foreach($upd_data as $u_data){
    //print_r($u_data);
    $upd_settings = $db->prepare("UPDATE settings SET domain_id=:domain_id, `key`=:key, value=:value WHERE id=:id");
    $upd_settings->execute($u_data);



}



    Header('Location: '.$_SERVER['PHP_SELF']);
    Exit(); //optional

}









?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar opciones</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <script src="https://cdn.tiny.cloud/1/dq94h9dhc5m44acyvcgi93poe1z2gry7uze9n4sqdbajxhas/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
  /*
  tinymce.init({
      selector:'#main_text',
      plugins: 'code',
      toolbar: 'code',
      menubar: 'tools'
      });
  
*/

var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
  selector: '#main_text',
  plugins: 'print preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap  quickbars linkchecker emoticons advtable',
  menubar: 'file edit view insert format tools table tc help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
  image_advtab: true,
  height: 600,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table configurepermanentpen',
  a11y_advanced_options: true,

});


  </script>
  <style>
  
  .back-to-top {
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: none;
}
  </style>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">AUTOMATICA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#aopciones">OPCIONES</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#acron">CRON</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#amenu">MENU</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#akeyword">KEYWORDS</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#atext">TEXTOS</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>
    </br>
    <div class="m-0 p-0 container-fluid">
    <div class="container mb-5">




    <div class="card">
        <div class="card-body">
            <form action="" method="post">
            <input type="hidden" name="settings" value="1">

<?php

    $domain = $db->query('SELECT id, domain FROM domains WHERE id=1')->fetch();

    echo'<div class="row"><h2 id="aopciones">OPCIONES</h2><div class="col-sm"><label for="menu">DOMINIO</label>';

    if(!empty($domain)){

        echo '<input type="hidden" name="domain_id" id="domain_id" class="form-control form-control-lg" value="'.$domain['id'].'">';

        echo '<input type="text" name="domain" id="domain" class="form-control form-control-lg" value="'.$domain['domain'].'" readonly>';
        echo'</div></div>';

    }else{

        echo '<input type="hidden" name="setup" id="setup" class="form-control form-control-lg" value="1">';
        
        echo '<input type="hidden" name="domain_id" id="domain_id" class="form-control form-control-lg" value="1">';

        echo '<input type="text" name="domain" id="domain" class="form-control form-control-lg" value="">';


        echo'</div></div>';


        echo'<div class="row">
        <div class="col-sm">
            <input type="submit" class="btn btn-primary btn-lg mt-3 d-block" value="GUARDAR" />
        </div>
    </div>';

        echo '</form>';



        die();
    }

    

            
?>




                <div class="row">
                    <div class="col-sm">
                            <label for="sitename">Nombre del sitio</label>
                            <input type="text" name="sitename" id="sitename" class="form-control form-control-lg" value="<?= $settings['sitename']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="slogan">Slogan del sitio</label>
                            <input type="text" name="slogan" id="slogan" class="form-control form-control-lg" value="<?= $settings['slogan']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <label for="description">Descripcion SEO</label>
                        <textarea name="description" id="description" cols="30" rows="3" class="form-control form-control-lg"><?= $settings['description']; ?></textarea>
                    </div>
                </div>
                <div class="col-sm">
                        <label for="style_menu">TIPO DE MENU</label>
                        <select name="style_menu" id="style_menu" class="form-select form-select-lg">
                        <?php


                            $styles_options['vertical'] = 'Vertical';
                            $styles_options['horizontal'] = 'Horizontal';
                            $styles_options['vertical-nobg'] = 'Vertical sin fondo';
                            $styles_options['horizontal-nobg'] = 'Horizontal sin fondo';

                            foreach($styles_options as $k => $v){
                                echo '<option value="'.$k.'"'.(($k==$settings['style_menu']) ? "selected" : "").'>'.$v.'</option>';
                            }


                        ?>

                        </select>
                </div>

                <div class="row">
                    <div class="col-sm">
                            <label for="main_color">Color principal</label>
                            <input type="text" name="main_color" id="main_color" class="form-control form-control-lg" placeholder="#F89C0E" value="<?= $settings['main_color']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="secondary_color">Color secundario</label>
                            <input type="text" name="secondary_color" id="secondary_color" class="form-control form-control-lg" placeholder="#CCCCCC" value="<?= $settings['secondary_color']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="main_color">Color texto principal</label>
                            <input type="text" name="maintext_color" id="maintext_color" class="form-control form-control-lg" placeholder="#FFFFFF" value="<?= $settings['maintext_color']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="secondary_color">Color texto secundario</label>
                            <input type="text" name="secondarytext_color" id="secondarytext_color" class="form-control form-control-lg" placeholder="#222222" value="<?= $settings['secondarytext_color']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="amazon_tag">Amazon tag-ID</label>
                            <input type="text" name="amazon_tag" id="amazon_tag" class="form-control form-control-lg" placeholder="usuario-21" value="<?= $settings['amazon_tag']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="apikey">Amazon Api Key</label>
                            <input type="text" name="apikey" id="apikey" class="form-control form-control-lg" value="<?= $settings['apikey']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="apikey">Amazon Secret Key</label>
                            <input type="text" name="apisecret" id="apisecret" class="form-control form-control-lg" value="<?= $settings['apisecret']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="interlinking">InterLinking</label>
                            <input type="number" name="interlinking" id="interlinking" class="form-control form-control-lg" value="<?= $settings['interlinking']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="cachedir">Ruta directorio Cache</label>
                            <input type="text" name="cachedir" id="cachedir" class="form-control form-control-lg" placeholder="cache/" value="<?= $settings['cachedir']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="logo">Ruta logo</label>
                            <input type="text" name="logo" id="logo" class="form-control form-control-lg" placeholder="/assets/logo.svg" value="<?= $settings['logo']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="link_color">Color de enlaces</label>
                            <input type="text" name="link_color" id="link_color" class="form-control form-control-lg" placeholder="#FF0000" value="<?= $settings['link_color']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="google_tag">Google Analytics Tag (G-XXXXX)</label>
                            <input type="text" name="google_tag" id="google_tag" class="form-control form-control-lg" placeholder="G-XXXXXXX" value="<?= $settings['google_tag']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="num_category_results">Resultados por categoria</label>
                            <input type="number" name="num_category_results" id="num_category_results" class="form-control form-control-lg" value="<?= $settings['num_category_results']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="num_results">Resultados en cabecera</label>
                            <input type="number" name="num_results" id="num_results" class="form-control form-control-lg" value="<?= $settings['num_results']; ?>">
                    </div>
                    <div class="col-sm">
                            <label for="num_related_results">Resultados articulos relacionados</label>
                            <input type="number" name="num_related_results" id="num_related_results" class="form-control form-control-lg" value="<?= $settings['num_related_results']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <label for="main_text">Texto de portada</label>
                        <textarea name="main_text" id="main_text" cols="30" rows="5" class="form-control form-control-lg" palceholder="{}"><?= $settings['main_text']; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <label for="source_text">Texto Spintax</label>
                        <textarea name="source_text" id="source_text" cols="30" rows="3" class="form-control form-control-lg" palceholder="{}"><?= $settings['source_text']; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="default_term">Keyword principal portada</label>
                            <input type="text" name="default_term" id="default_term" class="form-control form-control-lg" value="<?= $settings['default_term']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <input type="submit" class="btn btn-primary btn-lg mt-3 d-block" value="GUARDAR" />
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card mt-5">
    <div class="card-body">
        <h2 class="card-title" id="acron">INFORMACION CRON / TAREA PROGRAMADA</h2>
                <div class="row">
                    <div class="col-sm">
                        <p><strong>Version PHP necesaria:</strong> 7.3.26 o superior</p>
                        <p><strong>PATH API CRON:</strong> <?=$_SERVER["DOCUMENT_ROOT"];?>/inc/cron_api.php (Plesk: <strong>httpdocs/inc/cron_api.php</strong>)</p>
                        <p><strong>PATH HTML CRON:</strong> <?=$_SERVER["DOCUMENT_ROOT"];?>/inc/cron_scrap.php (Plesk: <strong>httpdocs/inc/cron_scrap.php</strong>)</p>
                    </div>
                </div>


    </div>
</div>



    <?php

$s = $db->prepare("SELECT * FROM keywords");
$s->execute();



if($s->rowCount()>0){

?>
<div class="card mt-5">
    <div class="card-body">
        <h2 class="card-title" id="atext">AGREGAR TEXTOS</h2>
        <form action="" method="post">
            <input type="hidden" name="addtext" value="1">
                    <div class="col-sm">
                        <label for="menu">KEYWORD ID</label>
                        <input type="number" name="keyword_id" id="keyword_id" class="form-control form-control-lg">
                    </div>
                <div class="row">
                    <div class="col-sm">
                            <label for="spintitle">SPINTITLE</label>
                            <input type="text" name="spintitle" id="spintitle" placeholder="{}" class="form-control form-control-lg">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <label for="spintext">SPINTEXT</label>
                        <textarea name="spintext" id="spintext" cols="30" rows="3" placeholder="{}" class="form-control form-control-lg"></textarea>
                    </div>
                </div>

                <div class="row">
                        <div class="col-sm">
                            <input type="submit" class="btn btn-success btn-lg mt-3 d-block" value="INSERTAR" />
                            </br>
                        </div>
                    </div>           
        </form>
    </div>
</div>
<?php
    }
?>




    <div class="card mt-5">
    <div class="card-body">
        <h2 class="card-title" id="amenu">AGREGAR MENU</h2>
        <form action="" method="post">
            <input type="hidden" name="addmenu" value="1">

                <div class="row">
                    <div class="col-sm">
                        <label for="menu">MENU SUPERIOR</label>
                        <textarea name="menu" id="menu" cols="30" rows="3" class="form-control form-control-lg" placeholder="TEXT:SLUG:POSITION"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <input type="submit" class="btn btn-success btn-lg mt-3 d-block" value="INSERTAR" />

                    </div>
                </div>   
</br>
                <div class="row">
                    <div class="col-sm">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">ETIQUETA</th>
                                    <th scope="col">SLUG</th>
                                    <th scope="col">POSICION</th>
                                    <th scope="col">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($db->query('SELECT * FROM menu ORDER BY position ASC')->fetchAll() as $menu){
                                        echo '<tr><td>'.$menu['text'].'</td><td>'.$menu['url'].'</td><td>'.$menu['position'].'</td><td><a class="btn btn-danger" href="?delmenu='.$menu['id'].'" onclick="return confirm(\'estas seguro?\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                      </svg></a></td></tr>';
                                    }
                                    
                                ?>
                            </tbody>
                        </table>                     
                    </div>
                </div>


        </form>
    </div>
</div>








<div class="card mt-5">
    <div class="card-body">
        <h2 class="card-title" id="akeyword">AGREGAR KEYWORDS</h2>
        <form action="" method="post">
            <input type="hidden" name="addkeywords" value="1">


                    <div class="row">
                        <div class="col-sm">
                            <textarea name="keywords" id="keywords" cols="30" rows="3" class="form-control form-control-lg" placeholder="AMAZON_TERM:KEYWORD o KEYWORD por linea"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <input type="submit" class="btn btn-success btn-lg mt-3 d-block" value="INSERTAR" />
                            </br>
                        </div>
                    </div> 
        </form>

                    <div class="row">
                    <div class="col-sm">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">AMAZON TERM</th>
                                    <th scope="col">KEYWORD</th>
                                    <th scope="col">SLUG</th>
                                    <th scope="col">INDEXED?</th>
                                    <th scope="col">CACHE TYPE</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($db->query('SELECT * FROM keywords ORDER BY id ASC')->fetchAll() as $keyword){
                                        echo '<tr><td>'.$keyword['id'].'</td><td>'.$keyword['amazon_term'].'</td><td>'.$keyword['keyword'].'</td><td><a href="/'.$keyword['slug'].'" target="_blank">'.$keyword['slug'].'</a></td><td>';
                                        
                                        if($keyword['indexed'] == 1){
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#12D669" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                          </svg>';
                                        }else{
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dddddd" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                          </svg>';
                                        }
                                        
                                        echo '</td><td>'.$keyword['cache_type'].'</td><td><a class="btn btn-danger" href="?delkey='.$keyword['id'].'" onclick="return confirm(\'estas seguro?\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                      </svg></a></td></tr>';
                                    }
                                    
                                ?>
                            </tbody>
                        </table>                     
                    </div>
                </div>
           
    </div>
</div>









</br>



</div>
<a id="back-to-top" href="#" class="btn btn-dark btn-lg back-to-top" role="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
</svg></a>
<script>

$(document).ready(function(){
	$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
});

</script>
</body>
</html>
<?php


/*
function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }
*/





function clean($string)
{
 
    $string = trim($string);
    
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 

    $string = str_replace(
        array('Ő', 'Ẹ', 'Ă', 'Ǒ', 'Ũ', 'Ạ', 'Ộ', 'Ā', 'Ø', 'Ể','Ī','Ā','Ø','Ě','Ǐ'),
        array('O', 'E', 'A', 'O', 'U', 'A', 'O', 'A', 'O', 'E','I','A','O','E','I'),
        $string
    );

    $string = str_replace(
        array('ő', 'ẹ', 'ă', 'ǒ', 'ũ', 'ạ'),
        array('o', 'e', 'a', 'o', 'u', 'a'),
        $string
    );


    $string = str_replace(
        array('ộ', 'ā', 'ø', 'ể', 'ī', 'ā', 'ě', 'ǐ'),
        array('o', 'a', 'o', 'e', 'i', 'a', 'e', 'i'),
        $string
    );



    $string = str_replace(
        array('Ṽ', 'ḇ', 'ę', 'Ŝ', 'ę', 'ẋ', 'ụ', 'ş'),
        array('V', 'b', 'e', 'S', 'e', 'x', 'u', 's'),
        $string
    );

    $string = str_replace(
        array('ș','ǎ','ś','ḭ','ṙ','š','ṋ'),
        array('s', 'a', 's', 'i', 'r', 's', 'n'),
        $string
    );


    $string = str_replace(
        array('đ','ọ','ų','Ș','ų','ṩ'),
        array('d', 'o', 'u', 'S', 'u', 's'),
        $string
    );





    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    /*
    $string = str_replace(
        array("\", "¨", "º", "-", "~",
             "#", "@", "|", "!", """,
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );
 */
 
    return $string;
}



/*
function require_auth($AUTH_USER,$AUTH_PASS) {

    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
        !$has_supplied_credentials ||
        $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
        $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
    );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        exit;
    }
}
*/



function require_auth($AUTH_USER,$AUTH_PASS)
            {
                
                /*
                    RewriteEngine On
                    RewriteCond %{HTTP:Authorization} ^(.)
                    RewriteRule . - [e=HTTP_AUTHORIZATION:%1]
                */



                header('Cache-Control: no-cache, must-revalidate, max-age=0');

                if (! empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
                {
                    preg_match('/^Basic\s+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $AUTH_PASS);
                    
                    $str = base64_decode($AUTH_PASS[1]);
                    
                    list( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] ) = explode(':', $str);
                }
    
                $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

                $is_not_authenticated = (
                    !$has_supplied_credentials ||
                    $_SERVER['PHP_AUTH_USER'] != $AUTH_USER || $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
                );

                if ($is_not_authenticated) {
                    header('HTTP/1.1 401 Authorization Required');
                    header('WWW-Authenticate: Basic realm="Access denied"');
                    exit;
                }

            }


?>