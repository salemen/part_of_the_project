<?php
use yii\helpers\Html;
use app\models\other\PageConfig;

$page_url = Yii::$app->controller->getRoute();
$model = PageConfig::findOne(['page_url'=>$page_url]);

if ($model) {
    if ($model->page_content) {
        echo Html::tag('blockquote', $model->page_content);
    }
}