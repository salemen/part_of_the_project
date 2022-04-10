<?php
// Виджет для отображения flex-блоков

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class MenuItems extends Widget
{
    public $model;
    public $itemClass = 'item-20';
    public $showHeader = true;

    public function run()
    {
        $this->renderContent($this->model);
    }
    
    protected function renderContent($model)
    {
        if ($model) {            
            if ($model->menuItems) {
                if ($this->showHeader) {
                    echo Html::tag('h2', $model->name, ['class'=>'text-center text-primary']);
                }
                echo Html::beginTag('div', ['class'=>'flex-row']);
                foreach ($model->menuItems as $item) {
                    echo $this->renderItem($item);
                }
                echo Html::endTag('div');
            }
        }
        
        Yii::$app->view->registerCss('
            .flex-row {
                display: flex;
                flex-flow: row wrap;
                justify-content: center;
                margin-top: 3%;
            }
            .item { 
                margin: 6px 6px 40px;
                text-align: center;
            }
            .item-10 { 
                flex: 0 1 calc(10% - 12px);
            }
            .item-15 { 
                flex: 0 1 calc(15% - 12px);
            }
            .item-20 { 
                flex: 0 1 calc(23% - 12px);
            }
            .item-30 { 
                flex: 0 1 calc(30% - 12px);
            }
            .item-40 { 
                flex: 0 1 calc(40% - 12px);
            }
            .item-50 { 
                flex: 0 1 calc(50% - 12px);
            }
            .item > a > img { 
                max-width: 70%;
            }
            .item > a > p {
                color: #777777;
                font-size: 20px;
                text-transform: uppercase;
            }

            @media (max-width: 767px) {
                .item { 
                    flex: 0 1 calc(90% - 12px);
                    margin: 6px 6px 20px;
                    text-align: center;
                }
            }
        ');
    }    

    protected function renderItem($model) {
        $class = (Yii::$app->user->isGuest) ? $model->class_guest : $model->class_default;
        $img = Html::img($model->photo ? '/storage/menu-item/' . $model->photo : '/img/logo/logo-bird-sm.png', ['class'=>'img-center-responsive']);
        $name = Html::tag('p', $model->name, ['style'=>'margin-top: 5px;']);
        $text = $img . $name;        
        $link = Html::a($text, $model->url, ['class'=>$class, 'target'=>($model->is_blank) ? '_blank' : '_self']);

        return Html::tag('div', $link, ['class'=> implode(' ', ['item', $this->itemClass])]);
    }  
}