<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = 'Установление порядка';
$this->params['breadcrumbs'][] = ['label'=>'Виды исследований', 'url'=>['research-type/index']];
$this->params['breadcrumbs'][] = ['label'=>'Показатели', 'url'=>['index', 'type_id'=>$type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::button('Сохранить', ['id'=>'save-sort', 'class'=>'btn btn-success', 'data-url'=>Url::current(), 'style'=>'margin-right: 3px;']) . Html::a('Отмена', ['index', 'type_id'=>$type_id], ['class'=>'btn btn-danger'])
    ],
    'rowOptions'=>function ($model) { return ['data-id'=>$model->id]; },
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'id',
            'value'=>function ($model) {
                return $model->name;
            }
        ]
    ]
]) ?>

<?php
$this->registerJsFile('/plugins/jquery.ui/jquery.ui.min.js', ['depends'=>JqueryAsset::className()]);
$this->registerJs('
var data = {};

$("tbody").sortable({
    axis: "y",
    create: function( event, ui ) {
        data = $(this).sortable("toArray", {attribute: "data-id"});
    },
    update: function(event, ui) {
        data = $(this).sortable("toArray", {attribute: "data-id"});
    }
});
$(document).on("click", "#save-sort", function() {
    $.post($(this).attr("data-url"), {data: data});
}); 
');