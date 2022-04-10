<?php
use kartik\grid\GridView;
use app\helpers\AppHelper;
use yii\helpers\Html;
use app\models\test\TestGroup;
use app\models\test\TestResult;
use app\widgets\Search;

$this->title = 'Результаты тестов';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Search::widget(['model'=>$searchModel])
    ],
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'user_id',
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->employee) {
                    return Html::a($model->employee->fullname, ['user', 'id'=>$model->user_id], ['class'=>'btn-modal']);
                } elseif ($model->patient) {
                    return Html::a($model->patient->fullname, ['user', 'id'=>$model->user_id], ['class'=>'btn-modal']);
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'test_id',
            'value'=>'test.name'
        ],
        [
            'label'=>'Результаты',
            'format'=>'raw',
            'value'=>function ($model) {
                $groups = TestGroup::find()->select(['id','name'])->where(['test_id'=>$model->test_id])->all();
                $str_results = '';

                foreach ($groups as $group) {
                    $str = null;
                    $str_results .= $group->name . ':  ';
                    $result = TestResult::find()->select('result')->where(['session_id'=>$model->id, 'group_id'=>$group->id])->scalar();

                    switch ($result) {
                        case 0:
                        case ($result >= 0 && $result < 8):
                            $class = 'text-success';
                            $str = 'Нормальное состояние';
                            break;
                        case ($result >= 8 && $result < 11):
                            $class = 'text-warning';
                            $str = 'Субклинически выраженная тревога / Депрессия';
                            break;
                        case ($result >= 11):
                            $class = 'text-danger';
                            $str = 'Клинически выраженная тревога / Депрессия';
                            break;
                    }

                    $str_results .= AppHelper::declension($result, 'балл', 'балла', 'баллов') . ' - ' . Html::tag('span', $str, ['class'=>$class]);
                    $str_results .= '</br>';
                }

                return $str_results;
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view}'
        ]
    ]
]);