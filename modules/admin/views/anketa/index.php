<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Администрирование: Анкеты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Статус',
            'template'=>'{toggle-status}',
            'buttons'=>[
                'toggle-status'=>function ($url, $model) {
                    if ($model->status) {
                        return Html::a('<i class="fa fa-check text-success" style="font-size: 20px"></i>', $url);                    
                    } else {
                        return Html::a('<i class="fa fa-times text-danger" style="font-size: 20px"></i>', $url);
                    }
                }
            ]
        ],
        'name',
        'desc',       
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Вопросы',
            'template'=>'{anketa-question}',
            'buttons'=>[
                'anketa-question'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['anketa-question/index', 'anketa_id'=>$model->id]);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Риски',
            'template'=>'{anketa-risk-category}',
            'buttons'=>[
                'anketa-risk-category'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['anketa-risk-category/index', 'anketa_id'=>$model->id]);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Условия',
            'template'=>'{permissions}',
            'buttons'=>[
                'permissions'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['anketa-permission/index', 'anketa_id'=>$model->id]);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]) ?>