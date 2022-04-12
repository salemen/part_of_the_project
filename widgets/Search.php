<?php
// Поиск для admin модуля

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class Search extends Widget
{
    public $action = ['index'];
    public $model;

    public function run()
    {
        $model = $this->model;
        
        echo Html::beginTag('div', ['class'=>'box-tools', 'style'=>'float: right; top: 10px;']);
        $form = ActiveForm::begin(['id'=>'search-form', 'action'=>$this->action, 'method'=>'get', 'fieldConfig'=>['options'=>['tag'=>false]]]);
            echo Html::beginTag('div', ['class'=>'input-group pull-right', 'style'=>'width: 300px;']);     
                echo $form->field($model, 'search')->textInput(['class'=>'form-control', 'placeholder'=>'Поиск...'])->label(false);
                echo Html::beginTag('span', ['class'=>'input-group-btn']);
                    echo (isset($model->search)) ? Html::a('<i class="fa fa-remove"></i>', $form->action, ['class'=>'btn btn-default reset', 'style'=>'float: none;']) : null;
                    echo Html::submitButton('<i class="fa fa-search"></i>', ['class'=>'btn btn-default', 'style'=>'float: none;']);
                echo Html::endTag('span');            
            echo Html::endTag('div');
        $form->end(); 
        echo Html::endTag('div');
    }   
}