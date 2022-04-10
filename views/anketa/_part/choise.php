<?php
use app\models\anketa\AnketaSession;
use yii\helpers\Html;
use yii\helpers\Url;

$btnName = ($showName === true) ? $anketa->name : 'Заполнить онлайн';
$btnClass = ($showName === true) ? '' : 'btn btn-block btn-danger ';
$btnUrl = Url::to(['view', 'anketa_id'=>$anketa->id]);

if ($user->isGuest) {
    echo Html::a($btnName, Url::to(['view', 'anketa_id'=>$anketa->id]), ['class'=>$btnClass . 'btn-login']);
} else {
    $session = AnketaSession::find()->where(['anketa_id'=>$anketa->id, 'patient_id'=>$user->id, 'is_end'=>false])->orderBy(['created_at'=>SORT_DESC])->one();
    
    echo Html::a($btnName, $btnUrl, [
        'class'=>($session) ? $btnClass . 'btn-choose' : $btnClass,
        'session_id'=>($session) ? $session->id : null,
        'url'=>$btnUrl
    ]);
}