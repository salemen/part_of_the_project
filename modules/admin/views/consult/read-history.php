<?php
use app\helpers\CryptHelper;
use app\models\employee\Employee;
use app\models\consult\Consult;

$crypter = new CryptHelper(Yii::$app->params['cryptKey']);

function isEmployee($id)
{
    return Employee::find()->where(['id'=>$id])->exists();
}


if ($model->history) { ?>
<div class="direct-chat direct-chat-warning">  
    <div class="box-body">
        <div class="direct-chat-messages">
            <?php foreach ($model->history as $value) { ?>
            <div class="direct-chat-msg <?= isEmployee($value->message_by) ? '' : 'right' ?>">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name <?= isEmployee($value->message_by) ? 'pull-left' : 'pull-right' ?>"><?= isEmployee($value->message_by) ? 'Консультант' : 'Пациент' ?></span>
                  <span class="direct-chat-timestamp <?= isEmployee($value->message_by) ? 'pull-right' : 'pull-left' ?>">
                      <?php if(empty($value->created_at)) {
                     echo date('d-m-Y H:i') ?></span>
                 <?php   } else {
                       echo date('d-m-Y H:i', $value->created_at) ?></span>
               <?php  } ?>

                </div>
                <div class="direct-chat-text" style="margin: 5px 0 0 0;"><?= $crypter->decrypt($value->message) ?></div>
            </div>     
            <?php } ?>
        </div>       
    </div>
</div>
<?php } else {
  echo 'Истории переписки нет.';  
} ?>

<?php
$this->registerCss('
.direct-chat-messages {
    height: 60vh;
    overflow-x: hidden;
}    
');