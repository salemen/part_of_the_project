<?php
use yii\helpers\Html;
use app\models\employee\Employee;

$this->title = 'Общие сведения: Заполнение профиля';
$this->params['breadcrumbs'][] = ['label' => 'Общие сведения', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Заполнение профиля';
$request = Yii::$app->request;

$tab = $request->get('tab');


?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?= ($tab == null ? 'active' : '') ?>"><?= Html::a('Личные данные', '#profile', ['data-toggle' => 'tab', 'id' => 'profile-tab']) ?></li>
        <li class="<?= ($tab == 'work' ? 'active' : '') ?>"><?= Html::a('Образование и опыт работы', '#work', ['data-toggle' => 'tab', 'id' => 'work-tab']) ?></li>
        <li class="<?= ($tab == 'uslugi' ? 'active' : '') ?>"><?= Html::a('Типы услуг', '#uslugi', ['data-toggle' => 'tab', 'id' => 'uslugi-tab']) ?></li>
        <li class="<?= ($tab == 'payment' ? 'active' : '') ?>"><?= Html::a('Способ оплаты', '#payment', ['data-toggle' => 'tab', 'id' => 'payment-tab']) ?></li>

        <?= ($pass) ? Html::tag('li', Html::a('Пароль', '#pasw', ['data-toggle' => 'tab'])) : null ?>
    </ul>
    <div class="tab-content">
        <div id="profile" class="tab-pane <?= ($tab == null ? 'active' : '') ?>">
            <?= $this->render('_part/fill-profile', ['model' => $model, 'employee_payment' => $employee_payment, 'employee_consult' => $employee_consult, 'employee_payment' => $employee_payment, 'employee_degree' => $employee_degree, 'employee_document' => $employee_document, 'employee_category' => $employee_category]) ?>
        </div>

        <div id="work" class="tab-pane <?= ($tab == 'work' ? 'active' : '') ?>">
            <?= $this->render('_part/fill-work', ['model' => $model, 'employee_degree' => $employee_degree, 'employee_document' => $employee_document, 'employee_category' => $employee_category, 'employee_consult' => $employee_consult, 'employee_payment' => $employee_payment,'employee_position' => $employee_position]) ?>
        </div>
        <div id="uslugi" class="tab-pane <?= ($tab == 'uslugi' ? 'active' : '') ?>">
            <?= $this->render('_part/fill-uslugi', ['employee_consult' => $employee_consult, 'model' => $model, 'employee_degree' => $employee_degree, 'employee_document' => $employee_document, 'employee_category' => $employee_category, 'employee_payment' => $employee_payment]) ?>
        </div>
        <div id="payment" class="tab-pane <?= ($tab == 'payment' ? 'active' : '') ?>">
            <?= $this->render('_part/fill-payment', ['employee_payment' => $employee_payment, 'employee_consult' => $employee_consult, 'model' => $model, 'employee_degree' => $employee_degree, 'employee_document' => $employee_document, 'employee_category' => $employee_category]) ?>
        </div>

        <?= ($pass) ? Html::tag('div', $this->render('_part/pass-change', ['pass' => $pass]), ['class' => 'tab-pane', 'id' => 'pasw']) : null ?>
    </div>
</div>