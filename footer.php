    <footer>
        <nav>
        <?php

            if(!empty($ilink)){

                foreach($ilink as $link){
                    echo '<a href="/'.$link['slug'].'">'.($link['keyword']).'</a>';
                }

            }


        ?>  
        </nav>
        <div class="copy">&copy; <?= $settings['sitename']; ?></div>
    </footer>
    </div>
    <div class="rightbox"><?php include "blocks/rightbox.php"; ?></div>
  </div><!-- END mainwrap //-->
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>   
</body>
</html>
<?php
//test
?>