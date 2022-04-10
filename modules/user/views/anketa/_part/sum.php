<?php
use yii\helpers\Html;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaUserAnswer;

$key = 0;
$keys = [];
$sum = 0;

function sum($session_id, $question, $answers)
{
    $sum = 0;
    
    foreach ($answers as $answer) {
        $user_answer = AnketaUserAnswer::find()->where(['session_id'=>$session_id,'question_id'=>$question->id,'answer'=>$answer->id])->one();
        if ($user_answer) {
            $sum += $answer->cost;
        }
    }
    
    return $sum;
}

foreach ($questions as $question) {
    ++$key;
    $answers = $question->anketaAnswers;
    $questions_2 = AnketaQuestion::findAll(['parent_id'=>$question->id]);

    if ($answers) {
        if ($answers[0]->cost !== null) { 
            $keys[] = $key;
            
            if ($session_id) {
               $sum += sum($session_id, $question, $answers); 
            }
        }
    }
    
    foreach ($questions_2 as $key_2=>$question_2) {
        if ($question->type != AnketaQuestion::TYPE_MAIN) { $key++; }
        $answers_2 = $question_2->anketaAnswers;
        $questions_3 = AnketaQuestion::findAll(['parent_id'=>$question_2->id]);
                
        if ($answers_2) {
            if ($answers_2[0]->cost !== null) {
                //если есть баллы у подвопроса
                $keys[] = $key . '.' . $key_2+1;
                
                if ($session_id) {
                    $sum += $this->sum($session_id, $question_2, $answers_2);
                }
                
                continue; 
            }
        }
        foreach ($questions_3 as $question_3) {
            $answers_3 = $question_3->anketaAnswers;

            if ($answers_3) {
                //если у подвопроса нет баллов, но есть у под-подвопроса
                if ($answers_3[0]->cost !== null) { 
                    $keys[] = $key . '.' . $key_2+1;
                    
                    if ($session_id) {
                        $sum += $this->sum($session_id, $question_2, $answers_3);
                    }
                    
                    break;  
                }
            }
        }
    }
}

if ($keys) {
    echo Html::beginTag('tr', ['align'=>'center']);
        echo Html::beginTag('td', ['colspan'=>$colspan+1]);
            echo 'ОБЩАЯ СУММА БАЛЛОВ в ответах на вопросы № ';
            foreach ($keys as $i=>$key) {
                if ($i != count($keys)-1) { echo $key . ','; }
                else { echo $key; }
            }
            echo ' равна '. (($session_id) ? Html::tag('i', $sum, ['style'=>'font-weight: bold']) : '____') . ' баллов';
        echo Html::endTag('td');
    echo Html::endTag('tr');
}