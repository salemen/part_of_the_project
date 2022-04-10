<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = $this->title;

function showBtnGroup($types, $anketa_id) {
    $result = '';
    
    if ($types) {
        $result .= Html::beginTag('div', ['class'=>'btn-group']);
        $result .= Html::a('Добавить', ['#'], ['class'=>'btn btn-success dropdown-toggle', 'data-toggle'=>'dropdown', 'style'=>'margin-right: 3px;']);
        $result .= Html::beginTag('ul', ['class'=>'dropdown-menu', 'role'=>'menu']);
        
        foreach ($types as $type) {
            $result .= Html::tag('li', Html::a($type['name'], ['create', 'type_id'=>$type['type_id'], 'anketa_id'=>$anketa_id]));
        }
        
        $result .= Html::endTag('ul');
        $result .= Html::endTag('div');
    }
    
    return $result;
}
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>showBtnGroup($types, $anketa_id) . Html::a('Установить порядок', ['sort', 'anketa_id'=>$anketa_id], ['class'=>'btn btn-info']) . Search::widget(['model'=>$searchModel, 'action'=>['index', 'anketa_id'=>$anketa_id]])
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
        'name:ntext', 
        [
            'attribute'=>'type',
            'value'=>function($model) {
                return $model->typeName;
            }
        ],               
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>