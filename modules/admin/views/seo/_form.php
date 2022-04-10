<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'SEO: Консультанты', 'url'=>['advisor']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'seo_title')->textInput() ?>

<?= $form->field($model, 'seo_desc')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>