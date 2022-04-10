<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\research\ResearchIndex;
use app\models\research\ResearchLabRelation;
use app\models\research\ResearchMethod;
use app\models\research\ResearchType;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Показатели', 'url'=>['index', 'type_id'=>$model->type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'is_group')->checkbox() ?>

<div class="row">    
    <div class="col-md-2">
        <?= $form->field($model, 'parent_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(ResearchIndex::find()
                ->where(['IS', 'parent_id', null])
                ->andWhere(['type_id'=>$model->type_id])
                ->andFilterWhere(['!=', 'id', $model->id])
                ->orderBy('name')
                ->all(), 'id', 'name'),            
            'hideSearch'=>false,
            'pluginOptions'=>['allowClear'=>true, 'placeholder'=>'Выберите']
        ]) ?>
    </div> 
    <div class="col-md-3">
        <?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-5">
        <?= $form->field($model, 'name_alt')->textInput(['maxlength'=>true]) ?>
    </div>        
    <div class="col-md-2">
        <?= $form->field($model, 'type_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(ResearchType::find()->orderBy('name')->all(), 'id', 'name'),
            'pluginOptions'=>['disabled'=>true, 'placeholder'=>'Выберите']
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'rel_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(
                ResearchLabRelation::find()
                    ->joinWith(['labIndex', 'labType'])
                    ->where(['type_id'=>$model->type->rel_id])
                    ->orderBy(['research_lab_type.name'=>SORT_ASC, 'research_lab_index.name'=>SORT_ASC])
                    ->all(),
                    'index_id',
                    function ($item) { return $item->labIndex->name; },
                    function ($item) { return $item->labType->name; }
                ),
            'hideSearch'=>false,
            'pluginOptions'=>[
                'placeholder'=>'Выберите вид исследования из лаборатории'
            ]
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'grade_id')->widget(Select2::className(), [
            'data'=>[0=>'Количественный', 10=>'Качественный'],
            'pluginOptions'=>['placeholder'=>'Выберите']
        ]) ?>
    </div> 
    <div class="col-md-3">
        <?= $form->field($model, 'method_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(ResearchMethod::find()->orderBy('name')->all(), 'id', 'name'),
            'pluginOptions'=>['placeholder'=>'Выберите']
        ]) ?>
    </div> 
    <div class="col-md-3">
        <?= $form->field($model, 'method_alt_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(ResearchMethod::find()->orderBy('name')->all(), 'id', 'name'),
            'pluginOptions'=>['placeholder'=>'Выберите']
        ]) ?>
    </div> 
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
');