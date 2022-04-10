<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\vaccine\VacSickness;

$this->title = 'Заболевания и Вакцины';
$this->params['breadcrumbs'][] = ['label'=>'Вакцинация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">              
            <?= GridView::widget([
                'dataProvider'=>$dataProvider,
                'columns'=>[
                    ['class'=>'kartik\grid\SerialColumn'],
                    [
                        'attribute'=>'sick_id',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filterWidgetOptions'=>[
                            'data'=>ArrayHelper::map(VacSickness::find()->orderBy('name')->all(), 'id', 'name'),
                            'hideSearch'=>false,
                            'pluginOptions'=>[
                                'allowClear'=>true,
                                'placeholder'=>'Фильтр'
                            ]                            
                        ],
                        'label'=>'Заболевания от которых Вы должны быть вакцинированы',
                        'value'=>function($model) {
                            return $model->sickness->name;
                        }
                    ],
                    [
                        'class'=>'kartik\grid\ActionColumn',
                        'header'=>'Вакцины',
                        'template'=>'{vaccines}',
                        'buttons'=>[
                            'vaccines'=>function($url, $model) {
                                return Html::a('<span class="btn btn-xs btn-success">Вакцины</span>', ['vaccines', 'sick_id'=>$model->sick_id], ['class'=>'btn-modal']);
                            }
                        ]
                    ]
                ]
            ]) ?>
        </div>
    </div>
</div>

<?php
$this->registerCss('
.table > thead > tr > th {
    border-bottom: 2px solid #f4f4f4;
}
');