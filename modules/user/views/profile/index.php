<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CommonUser;
use app\models\anketa\AnketaSession;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\user\UserDocs;
use yii\widgets\ActiveForm;

$this->title = 'Общие сведения';
$this->params['breadcrumbs'][] = $this->title;
$user_id = Yii::$app->user->id;

$usernameHeader = Yii::$app->session->has('employee_santal') ? 'Логин' : 'Инд. номер';

$fullmenu = true;
if (Yii::$app->session->has('employee_santal')) {
    $model = Employee::findOne($user_id);

    if (!$model->isFillProfile()) {
        $fullmenu = false;
    }
}

?>

<?php if (!$fullmenu): ?>
    <div style="padding:10px;">
        <div class="col-md-12 col-sm-12 col-xs-12 profile-fill-div">
            <h1>Заполните профиль</h1>
            Сейчас вам доступен только режим просмотра. <br/>
            Для активации личного кабинета, пожалуйста, заполните свой профиль.<br/><br/>
            <a class="btn btn-block btn-primary" href="/user/profile/fill" style="max-width:200px;">Заполнить
                профиль</a>

        </div>
    </div>
<?php endif; ?>


<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="profile-user-image">
        <?= Html::img(CommonUser::getPhoto(), ['class' => 'img-responsive']) ?>
    </div>
    <div class="profile-update-btn">
        <?= Yii::$app->session->has('employee_santal') ? Html::a('Редактировать профиль', ['fill'], ['class' => 'btn btn-block btn-primary']) : Html::a('Редактировать', ['update'], ['class' => 'btn btn-block btn-primary']) ?>
    </div>
</div>

<div class="col-md-6 col-sm-6 col-xs-12">
    <div class="profile-fullname">
        <?= Html::tag('h3', $model->fullname) ?>
    </div>
</div>
<div class="col-md-3 col-sm-3 col-xs-12">
    <?php if (Yii::$app->session->has('employee_santal') && ($model->activity == 0 || $model->activity == 1)) : ?>
        <?php $form = ActiveForm::begin(['id' => 'profile-activity-form']) ?>
        <?= $form->field($model, 'activity')->textInput(['maxlength' => true, 'class' => 'form-control hidden', 'hidden' => true]) ?>
        <?= \yii\bootstrap\ToggleButtonGroup::widget([
            'id' => 'activity-toggle',
            'name' => 'activity_toggle',
            'type' => 'radio',
            'items' => [
                0 => 'нет',
                1 => 'да'
            ],
            'labelOptions' => [
                'class' => ['btn', 'btn-primary activityButton'],
                'wrapInput' => true,
                'onclick' => '  
            document.getElementById("employee-activity").value= this.getElementsByTagName("input")[0].value;
            document.getElementById("profile-activity-form").submit()'
            ],
            'value' => [$model->activity]
        ]); ?>
        <?php ActiveForm::end() ?>
        <br/>
    <?php endif; ?>
    <?php if (Yii::$app->session->has('employee_santal') && $model->activity == -1) : ?>
        <div style="padding-top:20px"><b style="color:red;">Неактивный консультант</b></div>
    <?php endif; ?>
</div>
<div class="col-md-3 col-sm-3 col-xs-12">
    <div class="profile-fullname">
        <?= Yii::$app->session->has('employee_santal') ? Html::a('ПОМОЩЬ', ['/user/help-doc'], ['class' => 'btn btn-lg btn-block btn-warning']) :
            Html::a('ПОМОЩЬ', ['/user/help-doc/pacient'], ['class' => 'btn btn-lg btn-block btn-warning']) ?>
    </div>
</div>


<div class="col-md-9 col-sm-9 col-xs-12">
    <div class="profile-params">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table detail-view'],
            'attributes' => [
                [
                    'attribute' => 'username',
                    'label' => $usernameHeader,
                    'value' => function ($model) {
                        return $model->username;
                    }
                ],
                [
                    'attribute' => 'user_birth',
                    'value' => function ($model) {
                        return $model->user_birth;
                    }
                ],
                [
                    'attribute' => 'sex',
                    'value' => function ($model) {
                        if ($model->sex === null) {
                            return $model->sex;
                        }
                        return ($model->sex) ? 'Мужской' : 'Женский';
                    }
                ],
                'city',
                'phone',
                [
                    'attribute' => 'phone_work',
                    'value' => function ($model) {
                        return ($model instanceof Employee) ? $model->phone_work : null;
                    },
                    'visible' => ($model instanceof Employee)
                ],
                'email',
                'snils',
                [
                    'attribute' => '',
                    'label' => 'Поликлиника',
                    'value' => function ($model) {
                        if ($model->data === null) {
                            return "-";
                        }
                        return ($model->data->clinic) ? $model->data->clinic : '-';
                    }
                ],
                [
                    'attribute' => '',
                    'label' => 'Полис ОМС (организация)',
                    'value' => function ($model) {
                        if ($model->data === null) {
                            return "-";
                        }
                        return ($model->data->polis_oms_org) ? $model->data->polis_oms_org : '-';
                    }
                ],
                [
                    'attribute' => '',
                    'label' => 'Полис ОМС (номер)',
                    'value' => function ($model) {
                        if ($model->data === null) {
                            return "-";
                        }
                        return ($model->data->polis_oms_number) ? $model->data->polis_oms_number : '-';
                    }
                ],

            ]
        ]) ?>
    </div>
    <div class="box-footer">

        <div class="row">
            <div class="col-md-4 col-xs-6">
                <div class="description-block">
                    <h5 class="description-header">Анкет</h5>
                    <span class="description-text"><?= AnketaSession::getAnketasCount($user_id) ?></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-6">
                <div class="description-block">
                    <h5 class="description-header">Документов</h5>
                    <span class="description-text"><?= UserDocs::getDocsCount($user_id) ?></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="description-block" style="border-right: none;">
                    <h5 class="description-header">Консультаций</h5>
                    <span class="description-text"><?= Consult::getConsultsCount($user_id) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>