<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мой доход';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::tag('h4', 'Мой доход: ' . round($sum, 2) . ' руб.', ['class'=>'text-primary', 'style'=>'font-size: 24px; margin-top: 0px;']) ?>

<?= GridView::widget([
    'id'=>'employee-profit',
    'dataProvider'=>$dataProvider,
    'pjax'=>true,
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'orderStatus',
            'format'=>'raw',
            'value'=>function($model) {
                if ($model->orderStatus == 10) {
                    return '<span style="color: green;"><i class="fa fa-check-circle"></i> Оплачено </span>';
                } else {
                    return '<span style="color: orangered;"><i class="fa fa-times-circle"></i> Не оплачено </span>';
                }                        
            }
        ],
        [
            'attribute'=>'customerNumber',
            'header'=>'Пациент',
            'value'=>function($model) {
                if ($model->employeePatient) {
                    return $model->employeePatient->fullname;
                } elseif ($model->patient) {
                    return $model->patient->fullname;
                } else {
                    return null;
                }    
            }   
        ],
        [
            'attribute'=>'orderCreatedDatetime',
            'value'=>function($model) {
                return date("d.m.Y г.", $model->orderCreatedDatetime);
            },       
        ],
        [
            'attribute'=>'shopSumAmount',
            'header'=>'Доход врача (руб)',
            'value'=>function($model) {
                return round($model->shopSumAmount * 0.5, 2);
            }      
        ]            
    ]
]) ?>