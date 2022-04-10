<?php
use yii\helpers\Html;
use app\models\anketa\AnketaQuestion;

echo Html::beginTag('div', ['class'=>'table-responsive']);
echo Html::beginTag('table', ['class'=>'table table-bordered']);

$key = 0;

foreach ($questions as $question) {
    if ($question->type == AnketaQuestion::TYPE_MAIN) {
        echo Html::beginTag('tr');
        echo Html::tag('td', ++$key);
        echo Html::tag('td', $question->name, ['colspan'=>$max_answer_count + 1]);
        echo Html::endTag('tr');
        
        $model = AnketaQuestion::findAll(['parent_id'=>$question->id]);            
        if ($model) {           
            foreach ($model as $key_2=>$question_2) {                
                echo $this->render('answers', [
                    'session_id'=>$session_id,
                    'question'=>$question_2,
                    'colspan'=>$max_answer_count,
                    'key_p'=>$key,
                    'key_c'=>$key_2
                ]);
                
                $model_2 = AnketaQuestion::findAll(['parent_id'=>$question_2->id]);
                if ($model_2) {                    
                    foreach ($model_2 as $key_3=>$question_3) {
                        echo $this->render('answers', [
                            'session_id'=>$session_id,
                            'question'=>$question_3,
                            'colspan'=>$max_answer_count,
                            'key_p'=>null,
                            'key_c'=>null
                        ]);
                    }                    
                }
            }            
        }        
    } else {        
        echo $this->render('answers', [
            'session_id'=>$session_id,
            'question'=>$question,
            'colspan'=>$max_answer_count,
            'key_p'=>++$key,
            'key_c'=>null
        ]);    
        
        $model_3 = AnketaQuestion::findAll(['parent_id'=>$question->id]);
        if ($model_3) {
            foreach ($model_3 as $key_4=>$question_4) {
                echo $this->render('answers', [
                    'session_id'=>$session_id,
                    'question'=>$question_4,
                    'colspan'=>$max_answer_count,
                    'key_p'=>++$key,
                    'key_c'=>null
                ]);
            }
        }
    }
}

echo $this->render('sum', [
    'questions'=>$questions,
    'colspan'=>$max_answer_count,
    'session_id'=>$session_id
]);

echo Html::endTag('table');
echo Html::endTag('div');