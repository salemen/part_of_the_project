<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;


$this->title = ($consult->is_special) ? (Yii::$app->params['specialConsult']['active'] ? Yii::$app->params['specialConsult']['name'] : 'Специальная консультация') : 'Мои консультации';
$this->params['dashboard'] = 'true';

$submitFormText = '<span class="hidden-xs">Отправить</span><span class="hidden-lg hidden-md"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>';

if ($consult->employee_id == $user->id) {
    $patient = ($consult->employeePatient) ? $consult->employeePatient : $consult->patient;
    $consultHeader = ($patient->fullname !== 'Аноним') ? $patient->fullname : $patient->phone;
} else {
    if (isset($consult->employee)) {
        $consultHeader = $consult->employee->fullname;
    } else {
        $consultHeader = null;
    }

}
?>

    <div id="consultMain" class="box box-primary direct-chat direct-chat-primary" data-consult_id="<?= $consult->id ?>"
         style="border-top-color: #3c8dbc; margin-bottom: 0;">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $consultHeader ?> <i class="fa fa-pencil typing"
                                                           style="visibility: hidden;">...</i></h3>
            <div class="box-tools pull-right">
                <?php
                if ($consult->employee_id == $user->id && !$consult->is_end) {
                    $consultEndText = '<span class="hidden-xs">Завершить консультацию</span><span class="hidden-lg hidden-md"><i class="fa fa-remove"></i></span>';
                    echo Html::a($consultEndText, ['consult-end'], ['class' => 'btn btn-xs btn-danger btn-flat consult-end', 'style' => 'margin: 4px 0;']);
                }
//                if ($consult->is_end) {
//                    $consultHideText = '<span class="hidden-xs">Скрыть консультацию</span><span class="hidden-lg hidden-md"><i class="fa fa-eye"></i></span>';
//                    echo Html::a($consultHideText, ['consult-hide'], ['class' => 'btn btn-xs btn-danger btn-flat consult-hide', 'style' => 'margin: 4px 0;']);
//                }
                ?>
            </div>
        </div>

        <div class="box-body">
            <div class="direct-chat-messages">
                <?php if ($messages) {
                    foreach ($messages as $message) {
                        echo $this->render('message', ['consult' => $consult, 'crypter' => $crypter, 'model' => $message]);
                    }
                } ?>
            </div>
        </div>

        <?php if (!$consult->is_end) { ?>
            <div class="box-footer">
                <?php $form = ActiveForm::begin([
                    'action' => null,
                    'options' => [
                        'data' => [
                            'action-message-check' => Url::to(['message/check']),
                            'action-message-file' => Url::to(['message/file']),
                            'action-message-render' => Url::to(['message/render']),
                            'action-message-save' => Url::to(['message/save']),
                            'action-message-update' => Url::to(['message/update'])
                        ],
                        'enctype' => 'multipart/form-data'
                    ],
                    'errorCssClass' => null,
                    'successCssClass' => null
                ]) ?>

                <div class="input-group">
                    <?= $form->field($formModel, 'file')->fileInput(['class' => 'send-file-input', 'style' => 'display: none !important;'])->label(false)->error(false) ?>
                    <div class="input-group-btn">
                        <?= (Yii::$app->params['videoConsultAllowed']) ? Html::a('<i class="fa fa-video-camera"></i>', ['video', 'id' => $consult->id], ['class' => 'btn', 'style' => 'cursor: pointer;', 'title' => 'Видео-консультация']) : null ?>
                        <?= Html::a('<i class="fa fa-paperclip"></i>', false, ['class' => 'btn send-file', 'style' => 'cursor: pointer;', 'title' => 'Добавить вложение']) ?>
                    </div>
                    <?= $form->field($formModel, 'message')->textarea(['rows' => 3,
                        'autocomplete' => 'off',
                        'class' => 'form-control message-input',
                        'placeholder' => 'Введите сообщение'
                    ])->label(false)->error(false) ?>
                    <span class="input-group-btn dropup">
                    <?= Html::button($submitFormText, ['class' => 'btn btn-primary btn-flat send-message']) ?>
                </span>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        <?php } ?>
    </div>

<?php
$this->registerCss('
.direct-chat-messages {
    height: calc(100vh - 230px);
}
.direct-chat-text {
    margin: 5px 0 0 10px;
}
.right .direct-chat-text {
    margin-right: 10px;
}
.box-header>.fa, .box-header>.glyphicon, .box-header>.ion, .box-header .box-title {
    font-size: 16px;
}

@media (max-width: 767px) {
    .direct-chat-messages {
        height: calc(100vh - 270px);
    }
}
');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js', ['depends' => JqueryAsset::className()]);
$this->registerJsFile('/js/chat.js', ['depends' => JqueryAsset::className()]);