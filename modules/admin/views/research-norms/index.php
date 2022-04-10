<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\research\ResearchIndex;

$this->title = 'Нормы';
$this->params['breadcrumbs'][] = ['label'=>'Виды исследований', 'url'=>['research-type/index']];
$this->params['breadcrumbs'][] = ['label'=>'Показатели', 'url'=>['research-index/index', 'type_id'=>$index->type_id]];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'index_id'=>$index->id], ['class'=>'btn btn-success'])
    ],
    'responsive'=>false,
    'rowOptions'=>function($model){
        if ($model->index->grade_id === ResearchIndex::GRADE_QUAL && $model->is_norm) {
            return ['class'=>'success'];
        }
    },
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Статус',
            'template'=>'{toggle-status}',
            'buttons'=>[
                'toggle-status'=>function ($url, $model) use ($index) {
                    $class = ($model->status) ? 'fa fa-check text-success' : 'fa fa-times text-danger';
                    return Html::a("<i class='{$class}' style='font-size: 20px;'></i>", ['toggle-status', 'id'=>$model->id, 'index_id'=>$index->id]);
                }
            ]
        ],
        [
            'attribute'=>'index_id',
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->index->name;
            }
        ],
        [
            'attribute'=>'unit_id',
            'value'=>function ($model) {
                return $model->unit->name;
            }
        ],
        [
            'attribute'=>'index_id',
            'header'=>'Нормы муж.',
            'value'=>function ($model) {
                return ($model->index->grade_id === ResearchIndex::GRADE_COL) ? implode(' - ', [$model->norm_m_min, $model->norm_m_max]) : $model->norm_value;
            }
        ],
        [
            'attribute'=>'index_id',
            'header'=>'Нормы жен.',
            'value'=>function ($model) {
                return ($model->index->grade_id === ResearchIndex::GRADE_COL) ? implode(' - ', [$model->norm_w_min, $model->norm_w_max]) : $model->norm_value;
            }
        ],
        [
            'attribute'=>'index_id',
            'header'=>'Нормы берем.',
            'value'=>function ($model) {
                return ($model->index->grade_id === ResearchIndex::GRADE_COL) ? implode(' - ', [$model->norm_pr_min, $model->norm_pr_max]) : $model->norm_value;
            }
        ],
        [
            'attribute'=>'created_at',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],        
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}',
            'buttons'=>[
                'update'=>function ($url, $model) use ($index) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id'=>$model->id, 'index_id'=>$index->id], ['title'=>'Изменить']);
                },
                'delete'=>function ($url, $model) use ($index) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id'=>$model->id, 'index_id'=>$index->id], [
                        'data'=>[
                            'confirm'=>'Вы действительно хотите удалить данную запись?',
                            'method'=>'post'
                        ],
                        'title'=>'Удалить'
                    ]);
                }
            ]
        ]
    ]
]);