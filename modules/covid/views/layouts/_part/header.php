<?php
use yii\helpers\Html;
?>

<header class="main-header" style="background-color: #ffffff;">
    <a href="#" class="sidebar-toggle hidden-lg hidden-md hidden-sm" data-toggle="push-menu" role="button" style="padding: 15px 20px;">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <?= Html::a('Ситуационный центр COVID-19', Yii::$app->homeUrl, ['class'=>'logo', 'style'=>'font-family: MagistralB; color: #193e85;']) ?>    
</header>

<?php
$this->registerJs('
$(document).on("click", ".sidebar-toggle", function(e) {
    $.ajax({ url: "/site/collapse-sidebar" });
    e.preventDefault;
});
');