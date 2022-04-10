<?php
use yii\helpers\Html;
use app\helpers\AppHelper;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaUserAnswer;

$answers = $question->anketaAnswers;
$count = count($answers);
$type = $question->type;

if ($type == AnketaQuestion::TYPE_ONE || $type == AnketaQuestion::TYPE_MULTI) {
    if ($count > 2) {
        echo Html::beginTag('tr');
            echo Html::tag('td', ($key_p) . (($key_c !== null) ? '.' . ($key_c+1) : null));
            echo Html::tag('td', $question->name, ['colspan'=>$colspan]);
            echo Html::beginTag('tr');
                echo Html::tag('td', null);
                
                foreach ($answers as $answer) {
                    $user_answer = AnketaUserAnswer::find()->where(['session_id'=>$session_id,'question_id'=>$question->id,'answer'=>$answer->id])->one();
        
                    echo Html::beginTag('td');
                        $answ = ($user_answer) ? Html::tag('i', $answer->name, ['style'=>'font-weight: bold']) : $answer->name;
                        echo $answ . (($answer->cost !== null) ? '<br>(' . AppHelper::declension($answer->cost, 'балл', 'балла', 'баллов') . ')' : null);
                    echo Html::endTag('td');
                }
                
            echo Html::endTag('tr');
        echo Html::endTag('tr');
    } else {
        echo Html::beginTag('tr');
            echo Html::tag('td', ($key_p) . (($key_c !== null) ? '.' . ($key_c+1) : null));
            echo Html::tag('td', $question->name, ['colspan'=>$colspan - $count]);
            
            foreach ($answers as $answer) {
                $user_answer = AnketaUserAnswer::find()->where(['session_id'=>$session_id,'question_id'=>$question->id,'answer'=>$answer->id])->one();

                echo Html::beginTag('td');
                    $answ = ($user_answer) ? Html::tag('i', $answer->name, ['style'=>'font-weight: bold']) : $answer->name;
                    echo $answ . (($answer->cost !== null) ? '<br>(' . AppHelper::declension($answer->cost, 'балл', 'балла', 'баллов') . ')' : null);
                echo Html::endTag('td');
            }
            
        echo Html::endTag('tr');
    }
} elseif ($type == AnketaQuestion::TYPE_OPEN) {
    $user_answer = AnketaUserAnswer::find()->where(['session_id'=>$session_id,'question_id'=>$question->id])->one();
    
    echo Html::beginTag('tr');
        echo Html::tag('td', $key_p);
        echo Html::tag('td', $question->name, ['colspan'=>($user_answer) ? 1 : $colspan+1]);
        if ($user_answer) { echo Html::tag('td', Html::tag('i', $user_answer->answer, ['style'=>'font-weight: bold']), ['colspan'=>$colspan-1]); }
    echo Html::endTag('tr');
}