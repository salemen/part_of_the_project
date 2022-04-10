<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\research\ResearchIndex;

$this->title = 'Показатели';
$this->params['breadcrumbs'][] = ['label'=>'Виды исследований', 'url'=>['research-type/index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'type_id'=>$type_id], ['class'=>'btn btn-success', 'style'=>'margin-right: 3px;']) . 
        Html::a('Установить порядок', ['sort', 'type_id'=>$type_id], ['class'=>'btn btn-info', 'style'=>'margin-right: 3px;']) . 
        Html::a('Копировать нормы', ['copy-norms', 'type_id'=>$type_id], ['class'=>'btn btn-warning'])
    ],
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Статус',
            'template'=>'{toggle-status}',
            'buttons'=>[
                'toggle-status'=>function ($url, $model) {
                    $class = ($model->status) ? 'fa fa-check text-success' : 'fa fa-times text-danger';                    
                    return Html::a("<i class='{$class}' style='font-size: 20px;'></i>", $url);
                }
            ]
        ],
        [
            'attribute'=>'type_id', 
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->type->name;
            }
        ],
        [
            'attribute'=>'name',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(ResearchIndex::find()->where(['type_id'=>$type_id])->orderBy('name')->all(), 'name', 'name'),
                'hideSearch'=>false,
                'pluginOptions'=>['allowClear'=>true, 'placeholder'=>'Фильтр']                            
            ]
        ],
        [
            'attribute'=>'rel_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->rel ? $model->rel->name : null;
            }
        ],        
        [
            'attribute'=>'grade_id',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return ($model->grade_id == ResearchIndex::GRADE_COL) ? 'Количественный' : 'Качественный';
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
            'header'=>false,
            'template'=>'{research-norms}',            
            'buttons'=>[
                'research-norms'=>function ($url, $model) {
                    return Html::a('Нормы', ['research-norms/index', 'index_id'=>$model->id], ['class'=>'btn btn-primary btn-xs', 'title'=>'Нормы']);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>false,
            'template'=>'{sort}',            
            'buttons'=>[
                'sort'=>function ($url, $model) {
                    $childExists = ResearchIndex::find()->where(['parent_id'=>$model->id])->exists();
                    return ($childExists) ? Html::a('<span class="glyphicon glyphicon-th-list"></span>', ['research-index/sort', 'type_id'=>$model->type_id, 'parent_id'=>$model->id], ['title'=>'Установить порядок дочерних элементов']) : null;
                }
            ]
        ],        
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);