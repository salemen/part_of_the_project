<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Форма самонаблюдения';
$this->params['breadcrumbs'][] = ['label'=>'Дневник вакцинации COVID-19', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
?>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin() ?>
        <ul class="timeline">            
            <li>
                <i class="fa fa-site-font bg-blue">1</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Укажите жалобы, возникшие после вакцинации</h2>
                    <div class="timeline-body">
                        <?= $form->field($model, 'claims[common]')->checkboxList($model->getCombineValues('common', 'claims'), ['class'=>'diaryform-items'])->error(false)->label('Общие жалобы') ?>
                        
                        <?= $form->field($model, 'claims[domestic]')->checkboxList($model->getCombineValues('domestic', 'claims'), ['class'=>'diaryform-items'])->error(false)->label('Местные признаки') ?>
                        
                        <?= $form->field($model, 'claims[heart]')->checkboxList($model->getCombineValues('heart', 'claims'), ['class'=>'diaryform-items'])->error(false)->label('Сердечно-сосудистые нарушения') ?>
                        
                        <?= $form->field($model, 'claims[vision]')->checkboxList($model->getCombineValues('vision', 'claims'), ['class'=>'diaryform-items'])->error(false)->label('Нарушения со стороны органа зрения') ?>
                        
                        <?= $form->field($model, 'claims_other')->textarea(['class'=>'form-control', 'placeholder'=>'Необязательно', 'style'=>'resize: none;'])->error(false) ?>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-site-font bg-yellow">2</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Что вы делали при поялении признаков заболевания (недомогания)?</h2>
                    <div class="timeline-body">
                        <?= $form->field($model, 'actions')->checkboxList($model->getCombineValues('actions'))->error(false)->label(false) ?>
                        
                        <?= $form->field($model, 'actions_other')->textarea(['class'=>'form-control', 'placeholder'=>'Необязательно', 'style'=>'resize: none;'])->error(false) ?>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-site-font bg-green">3</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Важная дополнительная информация</h2>
                    <div class="timeline-body">
                        <?= $form->field($model, 'adds[pregnant]')->dropDownList($model->getCombineValues('pregnant', 'adds'))->error(false)->label('Было ли у вас наступление беременности в течение 3 месяцев после вакцинации?') ?>
                        
                        <?= $form->field($model, 'adds[contact]')->dropDownList($model->getCombineValues('contact', 'adds'))->error(false)->label('Был ли у вас после первого введения вакцины контакт с лицами, которым потом был поставлен диагноз COVID-19?') ?>
                        
                        <?= $form->field($model, 'adds[travel]')->dropDownList($model->getCombineValues('travel', 'adds'))->error(false)->label('Были ли у вас после вакцинации поездки в другие города и страны?') ?>
                        
                        <?= $form->field($model, 'adds[analysis]')->dropDownList($model->getCombineValues('analysis', 'adds'))->error(false)->label('Сдавали ли вы самостоятельно после вакцинации анализы на COVID-19?') ?>
                        
                        <?= $form->field($model, 'comment')->textarea(['class'=>'form-control', 'placeholder'=>'Необязательно', 'style'=>'resize: none;'])->error(false) ?>
                    </div>
                </div>
            </li>
        </ul>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class'=>'btn btn-lg btn-success']) ?>
        </div>
        <?php  ActiveForm::end() ?>
    </div>
</div>

<?php
$this->registerJs('
iCheckInit();

var items = [
    "#diaryform-claims-common",
    "#diaryform-claims-domestic",
    "#diaryform-claims-heart",
    "#diaryform-claims-vision",
    "#diaryform-actions"
];

$.each(items, function(key, itemId) {
    var itemKey = key;
    var itemLabels = $(itemId + " > label");
    
    if (itemLabels.length > 2) {
        var divId = "item-" + itemKey;
        $(itemId).append("<div id=" + divId + " class=\"panel-collapse collapse\"></div>");
        
        itemLabels.each(function(key, item) {
            if (key !== 0) {
                $(this).appendTo("#" + divId);
            }
        });
        
        $(itemId).append("<a class=\"others\" data-toggle=\"collapse\" href=\"#" + divId + "\">Показать другие варианты</a>");
    }
});

$(document).on("click", ".others", function (e) {
    $(this).css("display", "none");
});
');
$this->registerCss('
label {
    display: block;
}
.others {
    font-size: 13px;
    font-weight: 600;
}
');