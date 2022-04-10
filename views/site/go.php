<?php
use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['/go/to', 'id'=>$model->hash], true);
?>

<div class="text-center">
    <p>Ссылка для быстрого доступа: <?= $url ?></p>
    <?= Html::a('<i class="fa fa-files-o"></i> Скопировать ссылку', '#', ['class'=>'btn-clipboard', 'data-url'=>$url, 'title'=>'Скопировать в буфер обмена']) ?>
    <div id="qrcode" style="margin: 20px auto 0px; width: 60%;"></div>
</div>

<?php
$this->registerJsFile('/js/qrcode.min.js');
$this->registerJs('
new QRCode(document.getElementById("qrcode"), "' . $url .'");

$(".btn-clipboard").on("click", function(e) {
    var url = $(this).data("url");
    
    navigator.clipboard.writeText(url).then(() => {
        console.log("Скопировано");
    });
    e.preventDefault();
});
');