<?php
use app\core\app;
app::$title = "default/index";
app::$assets
    ->add('js/default/index/style.css')
    ->add('js/default/index/script.js');
?>

<p>default view index for default controller</p>
