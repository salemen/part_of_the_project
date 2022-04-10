<?php
use yii\helpers\Html;
?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini" style="font-size: 14px;"></span><span class="logo-lg"><b>Santal</b>Online</span>', '/', ['class'=>'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>        
    </nav>
</header>

<?php
$this->registerJs('
$(document).on("click", ".sidebar-toggle", function(e) {
    $.ajax({ url: "/site/collapse-sidebar" });
    e.preventDefault;
});
');