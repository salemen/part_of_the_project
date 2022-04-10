<?php
use yii\helpers\Html;

echo Html::tag('iframe', null, [
    'allowfullscreen'=>'',
    'frameborder'=>0,
    'src'=>"https://www.youtube.com/embed/{$url}?feature=oembed",
    'style'=>'max-height:600px; min-height: 400px; width: 100%;'        
]);