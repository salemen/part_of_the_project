<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мои анкеты';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Новое анкетирование', ['/anketa'], ['class'=>'btn btn-primary'])
    ],
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],                            
        [
            'attribute'=>'anketa_id',
            'label'=>'Название',
            'value'=>function ($model) {
                return $model->anketa->name;
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
            'template'=>'{view}',
            'buttons'=>[
                'view'=>function($url, $model) {
                    if ($model->is_end) {
                        return 
                            Html::beginTag('div', ['class'=>'btn-group']) .
                                Html::beginTag('button', ['class'=>'btn btn-xs btn-success', 'data-toggle'=>'dropdown']) .
                                    'Просмотр результатов ' . 
                                    Html::tag('span', null, ['class'=>'caret']) .
                                Html::endTag('button') .
                                Html::beginTag('ul', ['class'=>'dropdown-menu']) .
                                    Html::tag('li', Html::a('Результаты анкеты', $url)) .
                                    Html::tag('li', Html::a('Заключения по результатам', ['/user/anketa/view-risk', 'id'=>$model->id])) .
                                Html::endTag('ul') .
                            Html::endTag('div');
                    } else {
                        return Html::a('Продолжить заполнение анкеты', ['/anketa/view', 'anketa_id'=>$model->anketa_id], ['class'=>'btn btn-xs btn-danger']);
                    }                                        
                }
            ]
        ]
    ]
]);