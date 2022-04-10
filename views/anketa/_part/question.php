<?php
use yii\helpers\Html;
use app\models\anketa\AnketaQuestion;

$parentMain = AnketaQuestion::findOne(['id'=>$question->parent_id, 'type'=>AnketaQuestion::TYPE_MAIN]);
$parentQuestion = ($parentMain) ? $parentMain->name . '<br>' : null;

echo Html::tag('b', $parentQuestion . $question->name);

echo $this->render('answers', [
    'form'=>$form,
    'model'=>$model,    
    'question'=>$question
]);