<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\template\Template;

$this->title = 'Бланки осмотров';
$this->params['breadcrumbs'][] = $this->title;

function showBtnGroup() {
    $types = Template::getRelatedModels();
    
    if ($types) {
        $btnList = '';
        $main = Html::a('Добавить', ['#'], ['class'=>'btn btn-success dropdown-toggle', 'data-toggle'=>'dropdown']);        
        foreach ($types as $key=>$value) {
            $btnList .= Html::tag('li', Html::a($value['name'], ['create', 'type_id'=>$key]));
        }
        $ul = Html::tag('ul', $btnList, ['class'=>'dropdown-menu']);
        
        return Html::tag('div', $main . $ul, ['class'=>'btn-group', 'style'=>'margin-bottom: 10px;']);        
    }
    
    return null;
}

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    //'filterModel'=>$searchModel,
    'panel'=>[
        'before'=>showBtnGroup()
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'employee_id',
            'value'=>function ($model) {
                return ($model->employee) ? $model->employee->fullname : null;
            }
        ],
        [
            'attribute'=>'patient_id',
            'value'=>function ($model) {
                return ($model->patient) ? $model->patient->fullname : $model->patient_id;
            }
        ], 
        [
            'attribute'=>'type_id',
            'value'=>function ($model) {
                return Template::getRelatedModels()[$model->type_id]['name'];
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]);