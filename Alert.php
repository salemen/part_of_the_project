<?php
// Виджет красивых алертов и конфирмов (обязательно наличие jquery.confirm)

namespace app\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Alert extends Widget
{
    const TYPE_BLUE = 'blue';   
    const TYPE_DEFAULT = 'default';
    const TYPE_GREEN= 'green';
    const TYPE_RED = 'red';    
    const TYPE_ORANGE = 'orange';
    
    public $defaultType = self::TYPE_DEFAULT;
    public $useSessionFlash = true;
    
    public function init()
    {
        parent::init();
        
        if ($this->useSessionFlash) {
            $session = Yii::$app->getSession();
            $flashes = $session->getAllFlashes();
            
            foreach ($flashes as $type=>$data) {
                $data = (array)$data;
                foreach ($data as $option=>$value) {
                    $this->options[$option] = $value;
                }
                
                $session->removeFlash($type);
            }
        }        
    }
    
    public function run()
    {
        $this->registerAssets();
    }    
    
    protected function getOptions()
    {
        $this->options['type'] = ArrayHelper::getValue($this->options, 'type', $this->defaultType);

        return Json::encode($this->options);
    }
    
    protected function hasRequired()
    {
        $title = ArrayHelper::getValue($this->options, 'title');
        $content = ArrayHelper::getValue($this->options, 'content');
        
        if (empty($title) || empty($content)) {
            return false;
        }
        
        return true;
    }
    
    protected function registerAssets()
    {
        if ($this->hasRequired()) {
            $view = $this->getView();
            $js = "$.alert({$this->getOptions()});";
            $view->registerJs($js, $view::POS_END);
        }
    }
}