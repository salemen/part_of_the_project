<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\consult\ConsultDepartment */

$this->title = 'Create Consult Department';
$this->params['breadcrumbs'][] = ['label' => 'Consult Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consult-department-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
