<?php
use yii\helpers\Html;
use app\helpers\AppHelper;
?>

<div class="row">
    <div class="col-md-12">
        <?php
            echo Html::beginTag('div', ['class' => 'header-analysis']);
                echo Html::beginTag('div', ['class' => 'block-search']);
                    echo Html::input('text', 'Поиск', '', ['id'=>'input-search-analysis', 'class' => 'input-search-analysis', 'placeholder' => 'Поиск показателя']);
                    echo Html::tag('i', '', ['class' => 'fa fa-search icon-search']);
                echo Html::endTag('div');
                echo Html::tag('div', Html::tag('b', $type->name_alt ? $type->name_alt : $type->name), ['class' => 'analysis-name']);
            echo Html::endTag('div');
        ?>
        <div style="border: 1px solid #eee; margin: 10px 0; padding: 10px;">
            <div class="row">
            <?php if ($user->isGuest) { ?>
                    <div class="col-md-12">  
                        <div class="interpform-body">          
                        <?= 
                            Html::tag('div',
                                Html::tag('p', 'ФИО: ', ['class' => 'header-analysis__name']) . Html::activeInput('text', $model, 'user_fullname', ['class'=>'form-control-analysis form-control inline input-sm']),
                            ['class' => 'interpform-item interpform-fullname']) .
                            Html::tag('div',
                                Html::tag('p', 'Дата рождения: ', ['class' => 'header-analysis__name']) . Html::activeInput('date', $model, 'user_birthday', ['class'=>'form-control-analysis form-control input-sm']),
                            ['class' => 'interpform-item interpform-date']) .
                            Html::tag('div',
                                Html::tag('p', 'Пол: ', ['class' => 'header-analysis__name']) . Html::activeDropDownList($model, 'user_sex', ['man'=>'Мужской', 'woman'=>'Женский'], ['class'=>'form-control-analysis form-control inline input-sm']),
                            ['class' => 'interpform-item interpform-sex']) .
                            Html::tag('div',
                                Html::tag('p', 'Беременность: ', ['class' => 'header-analysis__name']) . Html::activeCheckbox($model, 'is_pregnant', ['class'=>'inline', 'label'=>false, 'style'=>'max-width: 110px;']),
                            ['class' => 'interpform-item interpform-pregnant']);
                        ?>
                        </div>  
                    </div> 
                    
                    <?php } else { ?>
                        <div class="col-md-12">  
                            <div class="interpform-body">
                                <div class="interpform-item interform-item--log interpform-fullname">
                                    <?= Html::tag('p', Html::tag('p', 'ФИО: ', ['class' => 'header-analysis__name']) . $model->user_fullname, ['id'=>'interpform-user_fullname', 'data'=>['value'=>$model->user_fullname]]) ?>
                                </div>
                                <div class="interpform-item interform-item--log interpform-date">
                                    <?= Html::tag('p', Html::tag('p', 'Дата рождения (возраст): ', ['class' => 'header-analysis__name']) . $model->user_birthday . ' (' . AppHelper::calculateAge($model->user_birthday, true) . ')', ['id'=>'interpform-user_birthday', 'data'=>['value'=>date('Y-m-d', strtotime($model->user_birthday))]]) ?>
                                </div>
                                <div class="interpform-item interform-item--log interpform-sex">
                                    <?= Html::tag('p', Html::tag('p', 'Пол: ', ['class' => 'header-analysis__name']) . ($model->user_sex ? 'Мужской' : 'Женский'), ['id'=>'interpform-user_sex', 'data'=>['value'=>$model->user_sex ? 'man' : 'woman']]) ?>
                                </div>
                                <div class="interpform-item interform-item--log interpform-pregnant">
                                    <?= $model->user_sex ? null : Html::tag('p', Html::tag('p', 'Беременность: ', ['class' => 'header-analysis__name']) . Html::activeCheckbox($model, 'is_pregnant', ['class'=>'inline', 'label'=>false, 'style'=>'max-width: 110px;'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?> 
                <div class="col-md-12 interpform-item interform-item__date-research">
                    <?= Html::tag('p', 'Дата исследования: ', ['class' => 'header-analysis__name']) . Html::activeInput('date', $model, 'research_date', ['class'=>'form-control-analysis form-control input-sm']) ?>
                </div>       
            </div>
        </div>        
    </div>
</div>

<?php
$this->registerJs('
$(document).ready(function(){
    $("#input-search-analysis").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".index-row").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            if(value.length <= 0) {
                $(".index-row__hide-table").css("display", "");
                console.log("Test");
            }
        });
    });
});
');