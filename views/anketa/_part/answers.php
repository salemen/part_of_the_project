<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\anketa\AnketaQuestion;

$type = $question->type;

echo Html::beginTag('div', ['style'=>'margin-top: 20px;']);

if ($type == AnketaQuestion::TYPE_ONE) {
    echo $form->field($model, 'answer')->radioList(ArrayHelper::map($question->anketaAnswers, 'id', 'name'))->label(false)->error(false);
} elseif ($type == AnketaQuestion::TYPE_MULTI) {
    echo $form->field($model, 'answer')->checkboxList(ArrayHelper::map($question->anketaAnswers, 'id', 'name'))->label(false)->error(false);
} elseif ($type == AnketaQuestion::TYPE_OPEN) {
    echo $form->field($model, 'answer')->textInput(['maxlength'=>true])->label(false)->error(false);
} elseif ($type == AnketaQuestion::TYPE_DATE) {
    echo $form->field($model, 'answer')->input('date', ['maxlength'=>true])->label(false)->error(false);
}

echo Html::endTag('div');

$this->registerCss('
#dynamicmodel-answer > label {
    margin-right: 30px;
}    
');