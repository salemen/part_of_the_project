<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\patient\Patient;

$this->title = 'Статистика: Пациенты';
$this->params['breadcrumbs'][] = $this->title;
$this->params['dashboard'][] = true;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'export'=>[
        'showConfirmAlert'=>false,
        'target'=>GridView::TARGET_BLANK
    ],
    'exportConfig'=>[
        GridView::EXCEL=>true
    ],
    'panel'=>[
        'heading'=>false
    ],
    'panelBeforeTemplate'=>'{toolbarContainer}<div class="pull-left">{summary}</div>{before}<div class="clearfix"></div>',
    'toolbar'=>[
        '{export}',
        '{toggleData}'
    ],
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'created_at',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>[
                    'currentMonth'=>'Текущий месяц',
                    'prevMonth'=>'Прошлый месяц'
                ] + ArrayHelper::map(Patient::find()->orderBy('created_at')->all(),
                    function ($item) { return date('Y', $item->created_at); },
                    function ($item) { return date('Y', $item->created_at); }
                ),
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'header'=>'Дата',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'attribute'=>'fullname',
            'filter'=>false,
            'mergeHeader'=>true
        ],
        [
            'attribute'=>'sex',
            'filter'=>false,
            'mergeHeader'=>true,
            'value'=>function ($model) {
                if ($model->sex === null) { return $model->sex; }
                return ($model->sex) ? 'Мужской' : 'Женский';
            }
        ],
        [
            'attribute'=>'phone',
            'filter'=>false,
            'mergeHeader'=>true
        ], 
        [
            'attribute'=>'email',
            'filter'=>false,
            'mergeHeader'=>true,
            'value'=>function($model) {
                if ($model->email == null || $model->email == '') {
                    return '(не задано)';
                }
                return $model->email;
            }
        ],
        [
            'attribute'=>'city',
            'filter'=>false,
            'mergeHeader'=>true,
            'value'=>function($model) {
                if ($model->city == null || $model->city == '') {
                    return '(не задано)';
                }
                return $model->city;
            }
        ]
    ]
]);
        
$this->registerCss('
.grid-view .summary {
    display: inline-block !important;
    vertical-align: middle;
}
');