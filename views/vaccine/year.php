<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\vaccine\VacAge;
use app\models\vaccine\VacSickness;

$this->title = 'Календарь вакцинации';
$this->params['breadcrumbs'][] = ['label'=>'Вакцинация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?= GridView::widget([
                'dataProvider'=>$dataProvider,
                'filterModel'=>$searchModel,
                'columns'=>[
                    ['class'=>'kartik\grid\SerialColumn'],
                    [
                        'attribute'=>'name',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filterWidgetOptions'=>[
                            'data'=>ArrayHelper::map(VacAge::find()->all(), 'name', 'name'),
                            'hideSearch'=>false,
                            'pluginOptions'=>[
                                'allowClear'=>true,
                                'placeholder'=>'Фильтр'
                            ]                            
                        ]
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'1',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                             
                            $sick_id = (isset($model->relations[0])) ? $model->relations[0]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'2',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[1])) ? $model->relations[1]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'3',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[2])) ? $model->relations[2]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'4',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[3])) ? $model->relations[3]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'5',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[4])) ? $model->relations[4]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'6',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[5])) ? $model->relations[5]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'7',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[6])) ? $model->relations[6]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ],
                    [
                        'attribute'=>'sick',
                        'label'=>'8',
                        'mergeHeader'=>true,
                        'value'=>function($model) {                
                            $sick_id = (isset($model->relations[7])) ? $model->relations[7]['sick_id'] : false;
                            return ($sick_id) ? VacSickness::getName($sick_id) : '';
                        }
                    ]
                ]
            ]) ?>
        </div>
    </div>
</div>

<?php
$this->registerCss('
.content-wrapper > .container {
    width: 100%;
}    
.table > thead > tr > th {
    border-bottom: 2px solid #f4f4f4;
}
');