<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\b2b\models\Cardio_one */

$this->title = 'Create Cardio One';
$this->params['breadcrumbs'][] = ['label' => 'Cardio Ones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cardio-one-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
