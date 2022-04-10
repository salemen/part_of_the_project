<?php
use yii\helpers\Html;
use app\models\research\ResearchIndex;

if ($values) {
    echo Html::beginTag('table', ['class'=>'table table-bordered', 'style'=>'margin-bottom: 0px; width: 98%;']);
        echo Html::beginTag('tbody');                       
        foreach ($values as $key=>$value) {
            $interpretation = ResearchIndex::getInterpretation($value['index_id'], $value['value'], $value['unit_id'], $sex, $age, false);                    
            switch ($interpretation['type']) {
                case 'orange':
                    $color = "#f39c12";
                    break;
                case 'green':
                    $color = "#009a10";
                    break;
                case 'red':
                    $color = "#eb2a23";
                    break;
                case 'dark':
                    $color = "#607d8b";
                    break;
            }
            echo Html::tag('tr', Html::tag('td', $interpretation['content'], ['style'=>"border-left: 4px solid {$color};"]), ['style'=>'border: none; height: 10px;']);
            echo Html::tag('tr', null, ['style'=>'border: none; height: 10px;']);
        }
        echo Html::endTag('tbody');
    echo Html::endTag('table');
}