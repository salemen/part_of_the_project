<?php
use yii\helpers\Html;
use app\models\other\PageConfig;

$page_url = Yii::$app->controller->getRoute();
$model = PageConfig::findOne(['page_url'=>$page_url]);

if ($model) {
    if ($model->page_content) {
        echo Html::beginTag('div', ['class'=>'box box-primary']);
            echo Html::beginTag('div', ['class'=>'box-body']);
                echo Html::tag('div', $model->page_content, ['style'=>'padding: 5px;']);
            echo Html::endTag('div');
        echo Html::endTag('div');
    }
}