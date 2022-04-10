<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\b2b\models\Cardio_oneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cardio Ones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cardio-one-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cardio One', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'employee_id',
            'patient_id',
            'ekg_date',
            'patient_height',
            //'patient_weight',
            //'patient_sicks:ntext',
            //'patient_drugs:ntext',
            //'patient_target:ntext',
            //'is_end',
            //'is_payd',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
