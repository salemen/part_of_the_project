<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\consult\Consult;

$this->title = 'Статистика: Онлайн-платежи';
$this->params['breadcrumbs'][] = $this->title;

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
    'panelBeforeTemplate'=>'{toolbarContainer}{before}<div class="clearfix"></div>',
    'toolbar'=>[
        '{export}',
        '{toggleData}'
    ],
    'responsive'=>false,
    'showPageSummary'=>true,
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
                ] + ArrayHelper::map(Consult::find()->orderBy('created_at')->all(),
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
            'attribute'=>'invoice_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true
        ],
        [
            'attribute'=>'service_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true
        ],
        [
            'attribute'=>'user_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true
        ],
        [
            'attribute'=>'pay_amount',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true
        ],
        [
            'attribute'=>'pay_result',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true,
            'pageSummary'=>function ($summary) { return $summary . ' руб.'; },
        ]
    ]
]);