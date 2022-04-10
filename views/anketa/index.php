<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Анкетирование';
$this->params['breadcrumbs'][] = $this->title;
        
$user = Yii::$app->user;
?>

<div class="row">
    <?= Html::a('<i class="fa fa-chevron-right"></i>', '#', ['class'=>'btn-aside-toggle']) ?>
    <div class="aside-column col-md-3">  
        <div id="affixBlock">
            <?= Html::a('<i class="fa fa-remove"></i>', '#', ['class'=>'btn-aside-toggle-mobile']) ?>
            <div class="box box-body box-primary">
                <?= $this->render('_part/filter', ['searchModel'=>$searchModel]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box box-body box-primary">
            <?php if ($model) { foreach ($model as $value) { ?>
                <div class="row">
                    <div class="col-md-9">
                        <div class="info-list">
                            <h4 style="font-size: 24px; margin-top: 0px;">
                                <?= $this->render('_part/choise', [
                                    'anketa'=>$value,
                                    'showName'=>true,
                                    'user'=>$user
                                ]) ?>
                            </h4>
                            <p><?= $value->desc ?></p>    
                        </div>
                    </div>
                    <div class="col-md-3" style="text-align: center;">
                        <div class="btn-block btn-group">
                            <?= $this->render('_part/choise', [
                                'anketa'=>$value,
                                'showName'=>false,
                                'user'=>$user
                            ]) ?>
                        </div>
                        <?= ($value->file) ? Html::a('Печатный вариант', Url::to(['/uploads/' . $value->file]), ['class'=>'btn btn-block btn-primary']) : null ?>
                    </div>
                </div>
                <hr>
                <div style="color: #eb2a23;">Функционал находится в разработке. Запуск планируется в ближайшее время</div>
            <?php }
                echo LinkPager::widget(['pagination'=>$pagination]);            
            } else { 
                echo 'Ничего не найдено.';
            } ?>
        </div>        
    </div>   
</div>

<?php
$this->registerJs('
$(document).on("click", ".btn-choose", function(e) {
    var session_id = $(this).attr("session_id");
    var url = $(this).attr("url");
    
    $.confirm({
        buttons: {
            old: {
                action: function () {
                    window.location.href = url;
                },
                btnClass: "btn-primary",
                text: "Продолжить"
            },
            new: {
                action: function () {
                    $.ajax({
                        data: {session_id: session_id},            
                        method: "post",            
                        success: function() {            
                            window.location.href = url;
                        },
                        url: "/anketa/clear-session"
                    });
                },
                text: "Начать заново"
            }
        },
        closeIcon: true,
        content: "Продолжить заполнение анкеты или начать заново?",
        theme: "modern",
        title: "Анкета была заполнена не полностью!"
    });    
    
    e.preventDefault();
});
');