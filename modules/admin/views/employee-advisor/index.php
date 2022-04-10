<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\employee\Employee;

$this->title = 'Менеджер: Консультанты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'employee_id',
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Employee::find()->where(['status'=>10])->orderBy('fullname')->all(), 'id', 'fullname'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]                            
            ],
            'value'=>function ($model) {
                $name = ($model->employee) ? $model->employee->fullname : '-';
                return $name;
            }
        ],
        [
            'attribute'=>'city',
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Employee::find()->where(['status'=>10])->orderBy('city')->all(), 'city', 'city'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]                            
            ],
            'header'=>'Город',
            'value'=>function ($model) {
                $name = ($model->employee) ? $model->employee->city : '-';
                return $name;
            }
        ],
        [
            'attribute'=>'cost',
            'mergeHeader'=>true,
            'header'=>'Стоимость первичная / вторичная',
            'value'=>function ($model) {
                return $model->cost . ' / ' . (($model->cost_2nd !== null) ? $model->cost_2nd : '-');
            }            
        ],
        [
            'attribute'=>'status',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>[10=>'Активен', 0=>'Не активен'],
                'hideSearch'=>true,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]                            
            ],
            'format'=>'raw',      
            'headerOptions'=>['class'=>'kv-align-center'],
            'value'=>function ($model) {
                if ($model->status == 10) {
                    return Html::a('<i class="fa fa-check text-success" style="font-size: 20px"></i>', false, ['title'=>'Консультант активен']);                    
                } else {
                    return Html::a('<i class="fa fa-times text-danger" style="font-size: 20px"></i>', false, ['title'=>'Консультант неактивен']);
                }
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]) ?>