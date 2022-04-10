<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\employee\Employee;

$this->title = 'SEO: Консультанты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
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
            'attribute'=>'seo_title',
            'value'=>function ($model) {
                $result = ($model->seo_title) ? : '-';
                return $result;
            }
        ],
        [
            'attribute'=>'seo_desc',
            'value'=>function ($model) {
                $result = ($model->seo_desc) ? : '-';
                return $result;
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]) ?>