<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AppHelper;
use app\models\employee\Employee;

$this->title = 'Результат расшифровки ЭКГ';
$this->params['breadcrumbs'][] = ['label'=>'Мои расшифровки ЭКГ', 'url'=>['/user/cardio']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-success btn-print'])) ?>

<div id="printDiv">
    <?= DetailView::widget([
        'model'=>$model,
        'attributes'=>[
            [
                'attribute'=>'id',
                'label'=>'Пациент',
                'value'=>function ($model) {
                    if ($model->cardio->patient == null) {
                        return Employee::findOne($model->cardio->patient_id)->fullname;
                    }

                    return $model->cardio->patient->fullname;
                }
            ],
            [
                'attribute'=>'id',
                'label'=>'Пол',
                'value'=>function ($model) {
                    return ($model->cardio->patient->sex) ? 'Мужской' : 'Женский';
                }
            ],
            [
                'attribute'=>'id',
                'label'=>'Дата рождения / Возраст',
                'value'=>function ($model) {
                    $dr = $model->cardio->patient->user_birth;
                    if (isset($dr)) {
                        return  $dr . ' / ' . AppHelper::calculateAge($dr, true);
                    }

                    return 'Дата рождения не указана';
                }
            ],
            [
                'attribute'=>'id',
                'label'=>'Рост / Вес',
                'value'=>function ($model) {
                    return $model->cardio->patient_height . ' см / ' . $model->cardio->patient_weight . ' кг';
                }
            ]
        ]
    ]) ?> 
    <?= DetailView::widget([
        'model'=>$model,
        'attributes'=>[
            [
                'attribute'=>'created_at',
                'label'=>'Дата снятия ЭКГ',
                'value'=>function ($model) {
                    return $model->cardio->ekg_date;
                }
            ],
            'p_1',
            'pq_1',
            'qrs_1',
            'qt_1',
            'rr_1',
            'deg_1',
            'chss_1',
            'eos_1',
            'rythm_1',
            'result',                    
            [
                'attribute'=>'created_at',                        
                'value'=>function ($model) {
                    return date('d.m.Y', $model->created_at);
                }
            ]
        ]
    ]) ?>            
</div>

<?php 
$this->registerJs('
$(document).on("click", ".btn-print", function() {
    var printContents = document.getElementById("printDiv").innerHTML;
    var originalContents = document.body.innerHTML;    
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
});  
');