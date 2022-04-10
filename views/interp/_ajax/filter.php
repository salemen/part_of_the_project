<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

echo Html::tag('p', 'Выберите искомый показатель.');

echo Select2::widget([
    'name'=>'interp-name',
    'options'=>[
        'class'=>'form-control',
        'id'=>'interp-filter-select'
    ],
    'pluginOptions'=>[
        'allowClear'=>true,        
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>Url::to(['filter', 'type_id'=>$type_id])
        ],
        'minimumInputLength'=>2,
        'placeholder'=>'Показатель',
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function(data) { return data.text; }')
    ]
]);

echo Html::a('Найти', '#', ['class'=>'btn btn-primary', 'id'=>'interp-filter-button', 'style'=>'margin-top: 10px;']);

$this->registerJs('
$(document).on("click", "#interp-filter-button", function(e) {
    var index_id = $("#interp-filter-select").val();
    
    if (index_id !== "") {
        $("#modal-form").modal("hide");
        var indexRow = $(".index-row[data-index_id=" + index_id + "]");

        if (indexRow.hasClass("collapse") && !indexRow.hasClass("in")) {
            $("#toggle_" + indexRow.data("parent_id")).click();
        }
        $("#interpform-values-" + index_id + "-value").focus();
        indexRow.addClass("animate-color");    
        setTimeout(function() {
            indexRow.removeClass("animate-color");
        }, 2000);
    }    
    
    e.preventDefault();
});    
');