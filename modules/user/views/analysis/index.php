<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\research\ResearchIndex;
use app\models\research\ResearchType;
use app\models\research\ResearchUnit;
use app\models\user\UserAnalysisProposal;

$this->title = 'Мои результаты анализов';
$this->params['breadcrumbs'][] = $this->title;

$buttons = Html::a('Добавить результаты анализов вручную', ['create'], ['class'=>'btn btn-success btn-modal', 'style'=>'margin-right: 3px;']);
$user = Yii::$app->user->identity;

if (UserAnalysisProposal::isExists($user->id)) {
    $buttons .= Html::button('<i class="fa fa-spinner fa-spin fa-fw"></i> Заявка в обработке', ['class'=>'btn btn-primary', 'disabled'=>true]);
} else {
    $buttons .= Html::a('Заявка на выгрузку результатов анализов из лаборатории', ['export-lab'], ['class'=>'btn btn-primary btn-modal']);
}

$descr = Html::tag('div',    
    Html::tag('span', '<i class="fa fa-circle" style="color: #009a10;"></i> Нормальное значение') . 
    Html::tag('span', '<i class="fa fa-circle" style="color: #f39c12;"></i> Сомнительное значение') .'<br>' .
    Html::tag('span', '<i class="fa fa-circle" style="color: #eb2a23;"></i> Отклонение от нормы') .
    Html::tag('span', '<i class="fa fa-circle" style="color: #607d8b;"></i> Неверное значение'),
    ['style'=>'float: right; text-align: right;']
);

echo GridView::widget([
    'id'=>'user-diagnosis',
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'panel'=>[
        'before'=>$buttons . $descr
    ],
    'responsive'=>false,
    'columns'=>[
        [
            'class'=>'kartik\grid\SerialColumn', 
            'hidden'=>true
        ],
        [
            'attribute'=>'type_id',
            'contentOptions'=>['class'=>'kv-align-middle', 'style'=>'border-bottom: 1px solid #ddd; width: 200px;'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(ResearchType::find()->orderBy('name')->all(), 'id', 'name'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>[
                    'allowClear'=>true,
                    'placeholder'=>'Фильтр'
                ]                            
            ],
            'format'=>'raw',
            'group'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'value'=>function ($model) {
                return Html::a($model->researchType->name, ['chart', 'type_id'=>$model->type_id]);
            }
        ], 
        [
            'attribute'=>'created_at',
            'contentOptions'=>['class'=>'kv-align-middle', 'style'=>'background-color: white !important; width: 100px;'],
            'header'=>'Дата',
            'group'=>true,
            'mergeHeader'=>true,
            'subGroupOf'=>1,
            'value'=>function ($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'attribute'=>'index_id',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->researchIndex->name;
            }
        ],
        [
            'attribute'=>'value',
            'contentOptions'=>['style'=>'width: 15%;'],
            'format'=>'raw',
            'mergeHeader'=>true,
            'value'=>function ($model) use ($user) {
                $sex = ($user->sex) ? 'man' : 'woman';                
                $interp = ResearchIndex::getInterpretation($model->index_id, $model->value, $model->unit_id, $sex);
                switch ($interp['type']) {
                    case 'orange':
                        $showInterp = true;
                        $style = 'color: #f39c12; font-size: 15px; font-weight: bold;';
                        $title = 'Введенное значение является сомнительным';
                        break;
                    case 'green':
                        $showInterp = false;
                        $style = 'color: #009a10;';
                        $title = 'Показатель в норме';
                        break;
                    case 'red':
                        $showInterp = true;
                        $style = 'color: #eb2a23; font-size: 15px; font-weight: bold;';
                        $title = 'Отклонение от нормы';
                        break;
                    case 'dark':
                        $showInterp = true;
                        $style = 'color: #607d8b;';
                        $title = 'Возможно значение введено неверно';
                        break;
                }
                
                $interpLink = $showInterp ? Html::a('<i class="fa fa-question"></i>', '#', [
                    'class'=>'btn btn-xs btn-default interp-result',
                    'data'=>[
                        'interp-content'=>$interp['content'],
                        'interp-type'=>$interp['type']
                    ],
                    'title'=>'Расшифровать'
                ]) : null;
                
                $value = Html::tag('span', $model->value, ['style'=>$style]);
                $unitName = ($model->researchIndex->grade_id == ResearchIndex::GRADE_COL) ? ResearchUnit::getUnitName($model->unit_id) : null;
                
                return implode(' ', [$value, $unitName, $interpLink]);
            }
        ],
        [
            'attribute'=>'index_id',
            'header'=>'Нормы',
            'mergeHeader'=>true,
            'value'=>function ($model) use ($user) {
                $norms = ResearchIndex::getNorms($model->index_id, $user->sex);
                return ($norms) ? $norms[0]['norms'] : '-';
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}',
            'buttons'=>[
                'update'=>function ($url, $model) {
                    if ($model->is_lab) {
                        $class = 'disabled';
                        $title = 'Нельзя изменить результаты, выгруженные из лаборатории';
                        $url = null;
                    } else {
                        $class = 'btn-modal';
                        $title = 'Изменить';
                        $url = $url;
                    }
                    
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class'=>$class, 'title'=>$title]);
                }
            ]
        ]
    ]
]);

$this->registerCss('
.table > thead > tr > th {
    border-bottom: 2px solid #f4f4f4;
}
');
$this->registerJs('
$(document).on("click", ".interp-result", function (e) {
    var content = $(this).data("interp-content");
    var type = $(this).data("interp-type");
    
    $.alert({
        columnClass: "col-md-5 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
        content: content,
        title: false,
        titleClass: "text-center",
        type: type
    });
    
    e.preventDefault();
});    
');