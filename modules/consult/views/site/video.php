<?php
use yii\web\View;

$this->title = 'Видео-консультация';
$this->params['dashboard'] = 'true';

$iceServers = json_encode(Yii::$app->params['iceServers']);
?>

<div class="video-block" id="videoDiv" data-room_id="<?= $model->created_at ?>" style="margin-top: 22px;">
    <div class="row">
        <div class="col-md-12">
            <video id="localVideo" autoplay muted height="20%" width="auto"></video>
            <video id="remoteVideo" autoplay controls disablepictureinpicture poster="/img/waiting.png" height="auto" width="100%"></video>       
            
            <div class="text-center">
                <button id="callButton" class="btn btn-lg btn-success"><i class="fa fa-phone"></i> Вызов</button>
                <button id="hangupButton"class="btn btn-lg btn-danger">Закончить вызов</button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss('
#easyrtcErrorDialog {
    right: 60px;
    top: 130px;
}
#localVideo {   
    border: 1px solid #ddd;
    height: 18vh;
    position: absolute;
    top: 0;
} 
#remoteVideo {
    background-color: black;
    border: 1px solid #ddd;
    border-radius: 3px;
    height: calc(100vh - 180px);    
}
video::-webkit-media-controls-play-button { display: none !important; }
video::-webkit-media-controls-timeline { display: none !important; }
');
$this->registerJs("
var iceServers = {$iceServers};   
var room = '{$model->id}'; 
", View::POS_HEAD);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js');
$this->registerJsFile('//webrtc.github.io/adapter/adapter-latest.js');
$this->registerJsFile('/js/webrtc.js');
$this->registerJs('
if (unsupportedBrowser()) {
    alert("Браузеры Internet Explorer и Safari не поддерживаются.");
    window.location.replace("/consult");
}
');