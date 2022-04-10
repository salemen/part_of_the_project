<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\MenuItems;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

$this->title = 'Онлайн-Поликлиника';
$this->params['wide-page'] = true;
?>

<script>
 function showSearchsearch(cart){
           $('#searchsearch .modal-body').html(cart);
           $('#searchsearch').modal();
       }
</script>

<div style="background-color: #ffffff;">
    <div class="container" style="padding-top: 30px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?= MenuItems::widget(['model'=>$itemFavorite, 'itemClass'=>'item-30', 'showHeader'=>false]) ?>
            </div>
        </div>
    </div>
</div>

<div style="background-color: #ecf0f5; padding: 30px 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <h3 style="font-weight: 600; margin: 10px 0px 5px;">ВЫ ПРЕДСТАВИТЕЛЬ МЕДИЦИНСКОЙ ОРГАНИЗАЦИИ?</h3>
                <div style="text-transform: uppercase;">Начните работу на нашей платформе!</div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12" style="padding-top: 10px;">
                <a  onclick="return showSearchsearch()" class="btn btn-primary btn-lg btn-block" >Зарегистрироваться! </i></a>
            </div>
        </div>
    </div>
</div>

<div style="background-color: #ffffff;">
    <div class="container" style="padding-top: 30px;">
        <div class="row">
            <div class="col-md-12">
                <?= MenuItems::widget(['model'=>$itemServices]) ?>
            </div>
        </div>
    </div>
</div>

<div style="background-color: #ffffff;">
    <div class="container" style="padding-top: 30px;">
        <div class="row">
            <div class="col-md-12">
                <?= MenuItems::widget(['model'=>$itemHealth]) ?>
            </div>
        </div>
    </div>
</div>

<div style="background-color: #ecf0f5; padding: 30px 0px;">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-12">
                <h3 style="font-weight: 600; margin: 10px 0px 5px;">НУЖНО БОЛЬШЕ ИНФОРМАЦИИ?</h3>
                <div style="text-transform: uppercase;">Не нашли, что искали? Имеются пожелания или замечания?</div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12" style="padding-top: 10px;">
                <?= Html::a('Связаться с нами!', ['/about/contact'], ['class'=>'btn btn-primary btn-lg btn-block']) ?>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_part/slider', ['model'=>$slider]) ?>


<?php       
 Modal::begin ([
     'header'=> '<h4>Кого Вы представляете ?</h4>',
     'id'=> 'searchsearch',
     'footer' => '',
             ]);
?>

 <form id="data" method="post" action="<?= Url::to(['doctors/reg']) ?>">
<div>
  <input class="custom-radio" type="radio" name="flexRadio" id="med">
  <label for="med">
    Медицинскую организацию
  </label>
</div>
<div>
  <input  class="custom-radio" type="radio" name="flexRadio" id="vrach" checked>
  <label for="vrach">
    Врача
  </label>
</div>
<div style="float: right;"><button  type="submit" class="btn btn-success" form="data">Продолжить </button></div>
</form>

<?php
Modal::end();
         
?>                               