<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AppHelper;
use app\models\cardio\CardioDocs;
use app\models\employee\Employee;

$this->title = 'Заявка №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Расшифровка ЭКГ', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= ($model->employee_id == null) ? Html::a('Приступить к исполнению', ['take-job', 'id'=>$model->id], ['class'=>'btn btn-success', 'data-method'=>'post']) : null ?>
    <?php if ($model->employee_id == Yii::$app->user->id && !$model->is_end) {
        echo Html::a('Загрузить результат расшифровки', ['result', 'id'=>$model->id], ['class'=>'btn btn-primary', 'style'=>'margin-right: 3px;']);
        echo ($model->cardioResult) ? Html::a('Закрыть заявку', ['close-job', 'id'=>$model->id], ['class'=>'btn btn-success', 'data-method'=>'post']) : null;
    } ?>
    <?= ($model->is_end) ? Html::a('Просмотр результатов расшифровки', ['view-result', 'id'=>$model->id], ['class'=>'btn btn-primary']) : null ?>
</p>

<?= DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'created_at',
            'label'=>'Дата заявки',
            'value'=>function ($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'attribute'=>'employee_id',
            'label'=>'Исполнитель',
            'value'=>function ($model) {
                if ($model->employee_id == null) {
                    return 'Исполнитель не назначен';
                } else {
                    return $model->employee->fullname;
                } 
            }
        ], 
        [
            'attribute'=>'is_end',
            'format'=>'raw',
            'label'=>'Статус заявки',
            'value'=>function ($model) {
                if ($model->employee_id == null && $model->is_end == false) {
                    return '<span class="text-danger">Ожидает исполнителя</span>';
                } elseif ($model->employee_id !== null && $model->is_end == false) {
                    return '<span class="text-warning">В работе</span>';
                } elseif ($model->is_end) {
                    return '<span class="text-success">Выполнена</span>';
                }
            }
        ]       
    ]
]) ?>

<hr>

<?= DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'patient_id',
            'value'=>function ($model) {                
                if ($model->patient == null) {
                    return Employee::findOne($model->patient_id)->fullname;
                }
                
                return $model->patient->fullname;
            }
        ],
        [
            'attribute'=>'patient_id',
            'label'=>'Дата рождения / Возраст',
            'value'=>function ($model) {
                $dr = $model->patient->user_birth;
                if (isset($dr)) {
                    return  $dr . ' / ' . AppHelper::calculateAge($dr, true);
                } else {
                    return 'Дата рождения не указана';
                }
            }
        ],
        [
            'attribute'=>'patient_id',
            'label'=>'Рост / Вес',
            'value'=>function ($model) {
                return $model->patient_height . ' см / ' . $model->patient_weight . ' кг';
            }
        ],         
        'ekg_date',        
        'patient_sicks:ntext',
        'patient_drugs:ntext',
        'patient_target:ntext'      
    ]
]) ?>

<hr>  

<?php if ($model->cardioDocs) { ?>
<div class="row">
    <div class="col-md-6">
        <h4>Снимки ЭКГ</h4>
        <?php foreach ($model->cardioDocs as $doc) {
            if ($doc->type == CardioDocs::TYPE_CURRENT) {
                $file = '/uploads/' . $doc->file;
                echo Html::tag('div', Html::a(Html::img($file, ['class'=>'img-responsive']), $file, ['class'=>'btn-magnific']), ['class'=>'col-md-3']);
            }            
        } ?>
    </div>
    <div class="col-md-6">
        <h4>Снимки предыдущих ЭКГ</h4>
        <?php foreach ($model->cardioDocs as $doc) {
            if ($doc->type == CardioDocs::TYPE_PREVIOUS) {
                $file = '/uploads/' . $doc->file;
                echo Html::tag('div', Html::a(Html::img($file, ['class'=>'img-responsive']), $file, ['class'=>'btn-magnific']), ['class'=>'col-md-3']);
            }            
        } ?>
    </div>
</div>        
<?php } ?>