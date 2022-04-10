<?php
use yii\helpers\Html;
use app\models\employee\Employee;

$this->title = 'Общие сведения: Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Общие сведения', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><?= Html::a('Профиль', '#profile', ['data-toggle' => 'tab']) ?></li>
        <?= ($pass) ? Html::tag('li', Html::a('Пароль', '#pasw', ['data-toggle' => 'tab'])) : null ?>
    </ul>
    <div class="tab-content">
        <div id="profile" class="tab-pane active">
            <?= $this->render(($model instanceof Employee) ? '_part/profile-employee' : '_part/profile-patient', ['model' => $model, 'advisor' => $advisor]) ?>
        </div>
        <?= ($pass) ? Html::tag('div', $this->render('_part/pass-change', ['pass' => $pass]), ['class' => 'tab-pane', 'id' => 'pasw']) : null ?>
    </div>
</div>