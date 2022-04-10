<?php
use yii\helpers\Html;
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaRiskGroup;
use app\models\anketa\AnketaRiskQuestion;
use app\models\anketa\AnketaUserAnswer;

$user = Yii::$app->user;
$user_id = $user->id;
$user_sex = $user->identity->sex;

echo Html::beginTag('div', ['class'=>'table-responsive']);
    echo Html::beginTag('table', ['class'=>'table table-bordered']);
        echo Html::beginTag('tr');
            echo Html::tag('th', 'Вопросы');
            echo Html::tag('th', 'Ответы');
            echo Html::tag('th', 'Заключение');
        echo Html::endTag('tr');

        foreach ($categories as $category) { 
            $groups = $category->anketaRiskGroups;
            
            foreach ($groups as $group) {
                if ($group->sex === null || $group->sex == $user_sex) {
                    switch ($group->type) {
                        case AnketaRiskGroup::NOTYPE: 
                            noType($group, $session_id, $anketa_id);
                            break;
                        case AnketaRiskGroup::TYPE_AND: 
                            andType($group, $session_id, $anketa_id);
                            break;
                        case AnketaRiskGroup::TYPE_OR: 
                            orType($group, $session_id, $anketa_id);
                            break;
                        case AnketaRiskGroup::TYPE_SUM: 
                            sumType($group, $session_id, $anketa_id);
                            break;
                    }
                }
            }
        }
    
    echo Html::endTag('table');
echo Html::endTag('div');

function noType($group, $session_id, $anketa_id)
{
    $question = AnketaRiskQuestion::findOne(['group_id'=>$group->id]);
    $answer = AnketaUserAnswer::findOne(['session_id'=>$session_id, 'question_id'=>$question->question_id, 'answer'=>$question->answer_id]);
    
    if ($answer) {
        echo Html::beginTag('tr');
            echo Html::tag('td', AnketaQuestion::findOne(['id'=>$question->question_id, 'anketa_id'=>$anketa_id])->name);
            echo Html::tag('td', AnketaAnswer::findOne(['id'=>$question->answer_id, 'question_id'=>$question->question_id])->name);
            echo Html::tag('td', $group->risk_name);
        echo Html::endTag('tr');
    }
}

function andType($group, $session_id, $anketa_id)
{
    $questions = $group->anketaRiskQuestions;
    
    foreach ($questions as $question) {
        $answer = AnketaUserAnswer::findOne(['session_id'=>$session_id, 'question_id'=>$question->question_id, 'answer'=>$question->answer_id]);
        
        if (!$answer) {
            return; //елси хотя бы одного нет
        }
    }
    
    echo Html::beginTag('tr');
        echo Html::beginTag('td');
            foreach ($questions as $question) {
                echo AnketaQuestion::findOne(['id'=>$question->question_id, 'anketa_id'=>$anketa_id])->name;
                echo '</br>';
            }
        echo Html::endTag('td');
        echo Html::beginTag('td');
            foreach ($questions as $question) {
                echo AnketaAnswer::findOne(['id'=>$question->answer_id, 'question_id'=>$question->question_id])->name;
                echo '</br>';
            }
        echo Html::endTag('td');
        echo Html::tag('td', $group->risk_name);
    echo Html::endTag('tr');
}

function orType($group, $session_id, $anketa_id)
{
    $questions = $group->anketaRiskQuestions;
    $arrays = [];
    
    foreach ($questions as $question) {
        $answer = AnketaUserAnswer::findOne(['session_id'=>$session_id, 'question_id'=>$question->question_id, 'answer'=>$question->answer_id]);
        
        if($answer) {
            $arrays[] = ['answer_id'=>$question->answer_id, 'question_id'=>$question->question_id];
        }
    }
    
    if (!empty($arrays)) {
        echo Html::beginTag('tr');
            echo Html::beginTag('td');
                foreach ($arrays as $array) {
                    echo AnketaQuestion::findOne(['id'=>$array['question_id'], 'anketa_id'=>$anketa_id])->name;
                    echo '</br>';
                }
            echo Html::endTag('td');
            echo Html::beginTag('td');
                foreach ($arrays as $array) {
                    echo AnketaAnswer::findOne(['id'=>$array['answer_id'], 'question_id'=>$array['question_id']])->name;
                    echo '</br>';
                }
            echo Html::endTag('td');
            echo Html::tag('td', $group->risk_name);
        echo Html::endTag('tr');
    }
}

function sumType($group, $session_id, $anketa_id)
{
    $questions = $group->anketaRiskQuestions;
    $sum = 0;
    
    foreach ($questions as $question) {
        $answer = AnketaUserAnswer::findOne(['session_id'=>$session_id, 'question_id'=>$question->question_id]);
        if ($answer) {
            $answer_id = $answer->answer;
            $q_type = AnketaQuestion::findOne(['id'=>$question->question_id])->type;

            if ($q_type == AnketaQuestion::TYPE_OPEN) {
                $sum += $answer_id;
            } else {
                $sum += AnketaAnswer::findOne(['id'=>$answer_id, 'question_id'=>$question->question_id])->cost;
            }
        }
    }
    
    if (checkSum($group, $sum)) {
        echo Html::beginTag('tr');
        echo Html::beginTag('td');
            foreach ($questions as $question) {
                echo AnketaQuestion::findOne(['id'=>$question->question_id, 'anketa_id'=>$anketa_id])->name;
                echo '</br>';
            }
        echo Html::endTag('td');
        echo Html::tag('td', 'Сумма баллов = ' . $sum);
        echo Html::tag('td', $group->risk_name);
    echo Html::endTag('tr');
    }
}

function checkSum($group, $sum)
{
    switch ($group->operator) {
        case '==': return ($sum == $group->value);
        case '!=': return ($sum != $group->value);
        case '>': return ($sum > $group->value);
        case '<': return ($sum < $group->value);
    }
}

