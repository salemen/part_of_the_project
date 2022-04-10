<?php
use yii\helpers\Html;
use app\models\consult\ConsultHistory;

$decryptedMessage = $crypter->decrypt($model->message);

switch ($model->message_type) {
    case ConsultHistory::TYPE_BOT:
    case ConsultHistory::TYPE_MSG:    
        $message = $decryptedMessage;
        break;
    case ConsultHistory::TYPE_FILE:
        $message = ConsultHistory::renderMessage($decryptedMessage);
        break;   
}

$class = 'direct-chat-msg ';
$date = date('d.m.Y | H:i', $model->created_at);
$user_id = Yii::$app->user->id;

if ($model->message_by == $user_id) {
    $actions = true;
    $classTime = 'pull-left';
    $classMsg = 'right';
    $classUser = 'pull-right';
    $fullname = 'Я';    
    $isRead = Html::tag('i', null, ['class'=>'fa fa-check check-message', 'style'=>['color'=>($model->is_read) ? 'green' : 'grey']]);    
} else {
    $actions = false;
    $classTime = 'pull-right';
    $classMsg = null;
    $classUser = 'pull-left';
    $fullname = 'Собеседник';
    $isRead = null;
}
?>

<div id="<?= "msg-{$model->id}" ?>" class="direct-chat-msg <?= $classMsg ?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-timestamp <?= $classTime ?>">
            <?= $isRead ?>
            <?= $date ?>
        </span>
        <span class="direct-chat-name <?= $classUser ?>"><?= $fullname ?></span>
    </div>
    <div class="direct-chat-text">
        <span class="msg-content"><?= $message ?></span>
        <?php if ($actions) { ?>
            <div class="btn-group dropup pull-right">
                <?= Html::button('<i class="fa fa-chevron-down" aria-hidden="true"></i>', ['class'=>'btn btn-xs dropdown-toggle', 'data'=>['toggle'=>'dropdown'], 'style'=>'background-color: transparent;']) ?>
                <ul class="dropdown-menu" style="bottom: -90%; right: 38px;">
                    <?= ($model->message_type == ConsultHistory::TYPE_MSG) ? Html::tag('li', Html::a('Редактировать', '#', ['class'=>'update-message', 'data'=>['message_id'=>$model->id]])) : null ?>
                    <?= Html::tag('li', Html::a('Удалить', ['message/delete'], ['class'=>'delete-message', 'data'=>['message_id'=>$model->id]])) ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>