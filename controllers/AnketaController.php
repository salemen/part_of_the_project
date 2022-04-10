<?php
// Анкетирование пациентов

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\models\CommonUser;
use app\models\anketa\Anketa;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaSession;
use app\models\anketa\AnketaUserAnswer;
use app\models\anketa\search\Anketa as AnketaSearch;

class AnketaController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                        
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    
    public function beforeAction($action) {
        if (!CommonUser::isProfileValid(Yii::$app->user->id)) {
            Yii::$app->session->setFlash('passReset', [
                'title'=>'Внимание!',
                'content'=>'Пожалуйста, заполните данные учетной записи полностью.',
                'type'=>'orange'
            ]);
            
            $redirectUrl = array_merge(['/user/profile/update'], ['redirect'=>Yii::$app->request->url]);
            $this->redirect($redirectUrl);
            
            return false;
        }
        
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new AnketaSearch();
        $dataProvider = (Yii::$app->user->isGuest) ? $searchModel->search($params) : $searchModel->searchByPermissions($params);
        $dataProvider->query->andWhere(['status'=>1])->orderBy('name');
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'model'=>$dataProvider->getModels(),
            'pagination'=>$dataProvider->pagination
        ]);
    }  
    
    public function actionView($anketa_id, $start_new = false)
    {
        $count = AnketaQuestion::find()
            ->where(['anketa_id'=>$anketa_id, 'status'=>true])
            ->andWhere(['!=', 'type', AnketaQuestion::TYPE_MAIN])
            ->count();
        
        if ($count === 0) {
            throw new NotFoundHttpException('Анкета находится на стадии заполнения. Пожалуйста зайдите позже!');
        }
        
        $anketa = Anketa::findOne($anketa_id);
        $session = $this->getSession($anketa_id);
        
        $prev_question = AnketaUserAnswer::find()
            ->select('question_id')
            ->where(['session_id'=>$session->id])
            ->orderBy(['id'=>SORT_DESC])
            ->scalar();
        
        return $this->render('view', [
            'anketa'=>$anketa,
            'session'=>$session,
            'count'=>$count,
            'prev_question'=>$prev_question
        ]);
    } 
    
    public function actionClearSession()
    {
        $session_id = Yii::$app->request->post('session_id');
        
        if ($session_id) {
            AnketaUserAnswer::deleteAll(['session_id'=>$session_id]);
        } else {
            throw new BadRequestHttpException('Отсутствуют обязательные параметры $session_id');
        }        
    }        
    
    public function actionEnd()
    {
        $session_id = Yii::$app->request->post('session_id');
        
        if ($session_id) {
            $session = AnketaSession::findOne($session_id);
            $session->updateAttributes(['is_end'=>true]);           
            Yii::$app->session->setFlash('anketa', ['title'=>'Внимание!', 'content'=>'Ваша анкета успешно сохранена', 'type'=>'green']);

            return $this->redirect(['/user/anketa/view', 'id'=>$session->id]);
        }
        
        throw new ServerErrorHttpException();        
    }        
 
    public function actionGetForm()
    {
        $anketa_id = Yii::$app->request->post('anketa_id');
        $session_id = Yii::$app->request->post('session_id');
        $prev_question = Yii::$app->request->post('prev_question');
        $question = $this->getQuestion($anketa_id, $session_id, $prev_question);
        
        $model = new DynamicModel(['anketa_id', 'question_id', 'session_id', 'answer']);
        if ($this->answerRequired($question)) {
            $model->addRule(['answer'], 'required');
        }        
                
        return $this->renderAjax('form', [
            'anketa_id'=>$anketa_id,
            'model'=>$model,
            'question'=>$question,
            'session_id'=>$session_id
        ]);
    } 
    
    public function actionGetProgress()
    {
        $count = Yii::$app->request->post('count');
        $prev_question = Yii::$app->request->post('prev_question');                    
        $question = AnketaQuestion::findOne($prev_question);
        $position = ($question) ? $question->position + 1 : 1;
        $progress = $position * 100 / $count;
        
        return $this->renderAjax('progress', [
            'count'=>$count,
            'position'=>$position,
            'progress'=>$progress
        ]);
    }
    
    public function actionSave()
    {        
        $post = Yii::$app->request->post()['DynamicModel'];
        
        if ($post) {       
            Yii::$app->response->format = 'json';
            $anketa_id = $post['anketa_id'];
            $question_id = $post['question_id'];
            $session_id = $post['session_id'];
            $answer = $post['answer'];
            
            if ($answer) {
                $type = AnketaQuestion::getQuestionTypeById($post['question_id']);
                
                if ($type == AnketaQuestion::TYPE_ONE || $type == AnketaQuestion::TYPE_OPEN ) {
                    $this->saveUserAnswer($session_id, $question_id, $answer);
                } elseif ($type == AnketaQuestion::TYPE_DATE) {
                    $this->saveUserAnswer($session_id, $question_id, date('d.m.Y', strtotime($answer)));
                } elseif ($type == AnketaQuestion::TYPE_MULTI) {
                    foreach ($answer as $answ) {
                        $this->saveUserAnswer($session_id, $question_id, $answ);
                    }
                }
            }
            
            $is_end = $this->checkIsEnd($anketa_id, $question_id, $answer);
            
            return [
                'is_end'=>$is_end,
                'question_id'=>$question_id
            ];
        }  
    }
    
    protected function getQuestion($anketa_id, $session_id, $prev_question = null)
    {          
        $position = null;
        
        if ($prev_question !== null) {
            $parentQuestion = AnketaQuestion::findOne($prev_question);
            $position = $parentQuestion->position;
        }
        
        return $this->checkParentAnswer($anketa_id, $position, $session_id, $prev_question);
    }
    
    protected function checkIsEnd($anketa_id, $question_id, $answer)
    {
        $last_question = AnketaQuestion::find()->select('id')->where(['anketa_id'=>$anketa_id])->orderBy(['position'=>SORT_DESC])->limit(1)->scalar();    

        if ($question_id == $last_question) {
            return true;
        } else {
            $child_question = AnketaQuestion::find()->where(['parent_id'=>$question_id])->one();
            
            if ($child_question->id == $last_question && $child_question->parent_answer_id != $answer) {
                return true;
            }
        }
        
        return false;
    }


    protected function checkParentAnswer($anketa_id, $position, $session_id, $prev_question)
    {
        $model = $this->getNextQuestion($anketa_id, $position);
        
        if ($model->parent_answer_id !== null) {
            $answer = AnketaUserAnswer::findOne(['session_id'=>$session_id, 'question_id'=>$prev_question]);
            if ($answer) {
                if ($answer->answer == $model->parent_answer_id) {
                    return $model;
                } else {
                    return $this->checkParentAnswer($anketa_id, $model->position, $session_id, $prev_question);
                }
            }
        }
        
        return $model;
    }
    
    protected function getNextQuestion($anketa_id, $position)
    {
        return AnketaQuestion::find()
            ->where(['anketa_id'=>$anketa_id, 'status'=>true])
            ->andWhere(['!=', 'type', AnketaQuestion::TYPE_MAIN])
            ->andFilterWhere(['>', 'position', $position])    
            ->orderBy('position')
            ->one();
    }

    protected function getSession($anketa_id)
    {
        $patient_id = Yii::$app->user->id;
        $model = AnketaSession::findOne(['anketa_id'=>$anketa_id, 'patient_id'=>$patient_id, 'is_end'=>false]);
        
        if (!$model) {
            $model = new AnketaSession(['anketa_id'=>$anketa_id, 'patient_id'=>$patient_id]);
            $model->save();
        }
        
        return $model;
        
    }        

    protected function saveUserAnswer($session_id, $question_id, $answer)
    {
        $answerExists = AnketaUserAnswer::find()->where(['session_id'=>$session_id, 'question_id'=>$question_id, 'answer'=>$answer])->exists();
        
        if ($answerExists) {
            return;
        }
        
        $model = new AnketaUserAnswer([
            'session_id'=>$session_id,
            'question_id'=>$question_id,
            'answer'=>$answer
        ]);
        
        return $model->save();
    }     
    
    protected function answerRequired($question)
    {
        if ($question) {
            $requiredChilds = AnketaQuestion::find()->where(['parent_id'=>$question->id])->andWhere(['!=', 'parent_answer_id', null])->exists();
            
            return ($requiredChilds) ? true : !$question->is_skip;
        }
        
        return false;
    }        
}