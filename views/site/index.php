<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\MenuItems;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

$this->title = 'Онлайн-Поликлиника';
$this->params['wide-page'] = true;
?>


<style>
    @media (min-width: 768px) {
    .modal-dialog {
        width: 600px;
        margin: 150px auto !important;
    }
        .boxform, .register-box {
            width: 500px;
            margin: 1% auto;
        }
    }
    .login-box-body, .register-box-body {
        padding: 1px!important;
    }
</style>
 <script>
 function showSearchsearch(cart){
           $('#searchsearch .modal-body').html(cart);
           $('#searchsearch').modal();
       }

  function showMod(cart){
           $('#mod .modal-body').html(cart);
           $('#mod').modal();
       }      
     
</script>
<div style="background-color: #ffffff;">

    <div class="container" style="padding-top: 30px;">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

     <?php if(Yii::$app->session->hasFlash('success')): ?>
        
            <script>
                
             setTimeout(function(){
                  $('#modd').trigger('click');
                }, 1000);

            </script>

          
           <?php endif; ?>
                <?= MenuItems::widget(['model'=>$itemFavorite, 'itemClass'=>'item-30', 'showHeader'=>false]) ?>

            </div>
        </div>
    </div>
</div>

<div style="background-color: #ecf0f5; padding: 30px 0px;">
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <h3 style="font-weight: 600; margin: 20px 0px 5px;">НАЧНИТЕ РАБОТУ НА НАШЕЙ ПЛАТФОРМЕ</h3>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12" style="padding-top: 10px;">
                <a  onclick="return showSearchsearch()" class="btn btn-primary btn-lg btn-block" >Зарегистрироваться! </i></a>
                 <a  onclick="return showMod()" id="modd"></i></a>
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
     'header'=> '<center><h4>Выберите тип регистрации</h4></center>',
     'id'=> 'searchsearch',
     'footer' => '',
             ]);?>
 
  <div class= "form_doc col-sm-6">
      <?= Html::a('Регистрация организации', ['/'], ['class' => 'btn btn-primary']) ?>
  </div>
  <div class="form_doc col-sm-6">
      <?= Html::a('Регистрация врача', ['/doctorreg/view'], ['class' => 'btn btn-primary']) ?>
   </div>


<?php
Modal::end();        
?>    

  <?php
    Modal::begin([
    'header' => null,
    'size' => 'modal-sl',
    'id' => 'mod',
    'footer' => null
   ]);?>
          <center> <div>  
    <p style="color:#193e85; font-weight:bold; font-size: medium;">Благодарим за выбор нашего сервиса-0323.ru</p>
    <p style="color:#193e85; font-weight:bold; font-size: medium;">В течение 24 часов наш специалист свяжется с Вами 
        для завершения регистрации на нашем портале</p>
     <a class="btn btn-primary" data-dismiss="modal">Замечательно</a>
     </div></center>

   <?php Modal::end();  ?>                          

  