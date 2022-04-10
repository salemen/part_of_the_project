<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\b2b\models\Cardio_one */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cardio Ones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cardio-one-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'employee_id',
            'patient_id',
            'ekg_date',
            'patient_height',
            'patient_weight',
            'patient_sicks:ntext',
            'patient_drugs:ntext',
            'patient_target:ntext',
            'is_end',
            'is_payd',
            'created_at',
        ],
    ]) ?>

</div>
