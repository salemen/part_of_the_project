<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\doctorreg */

$this->title = 'Create Doctorreg';
$this->params['breadcrumbs'][] = ['label' => 'Doctorregs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctorreg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
