<?php
// Виджет для озвучки текста в div#speak-text

namespace app\widgets\speaker;

use Yii;
use yii\bootstrap\Widget as BaseWidget;
use yii\helpers\Html;

class Widget extends BaseWidget
{    
    public $selector = 'div#speak-text';
    public $btnPlayText = '<i class="fa fa-play" aria-hidden="true"></i>';
    public $btnPauseText = '<i class="fa fa-pause" aria-hidden="true"></i>';
    public $btnStopText = '<i class="fa fa-stop" aria-hidden="true"></i>';
    public $speakText = null;
    
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        $this->registerAssets();
        
        $btnStart = Html::button($this->btnPlayText, ['class'=>'btn btn-primary btn-xs', 'id'=>'btn-play']);
        $btnPause = Html::button($this->btnPauseText, ['class'=>'btn btn-default btn-xs', 'id'=>'btn-pause']);
        $btnStop = Html::button($this->btnStopText, ['class'=>'btn btn-default btn-xs', 'id'=>'btn-stop']);
        
        $btnGroup = $this->speakText . implode(' ', [$btnStart, $btnPause, $btnStop]);
        
        echo $btnGroup;
    }
    
    protected function registerAssets()
    {
        $view = $this->getView();
        $view->registerJs("var selector = '" . $this->selector . "'", $view::POS_HEAD);
        Asset::register($view);
    }
}