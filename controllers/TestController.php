<?php
// Раздел "Тесты" (цунг, HADS)

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use app\helpers\AppHelper;
use yii\helpers\Html;
use app\models\employee\Employee;
use app\models\test\Test;
use app\models\test\TestAnswer;
use app\models\test\TestGroup;
use app\models\test\TestQuestion;
use app\models\test\TestResult;
use app\models\test\TestUserAnswer;
use app\models\test\TestUserSession;
use app\models\zung\ZungAnswers;
use app\models\zung\ZungPatients;
use app\models\zung\ZungQuestions;

class TestController extends Controller
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
       
    public function actionIndex()
    {
//        $model = Test::findOne($test_id);
//
//        return $this->render('index', [
//            'model'=>$model
//        ]);
        echo"сайт";
    }
    
    public function actionTest($test_id)
    {
        $model = new DynamicModel(['question', 'answer']);
        $model->addRule(['answer'], 'required');
        $questions = ($test_id == Test::ZUNG_TEST_ID) ? ZungQuestions::find()->all() : TestQuestion::findAll(['test_id'=>$test_id]);
        
        if (empty($questions)) {
            return $this->render('/site/error', ['name'=>'Ой!', 'message'=>'Страница находится на стадии заполнения. Пожалуйста зайдите позже!']);
        }
        
        if ($model->load(Yii::$app->request->post()) && $this->saveAnswers($model, $test_id)) {
            return $this->redirect(['/user/test', 'test_id'=>$test_id]);
        }

        return $this->render('test', [
            'model'=>$model,
            'questions'=>$questions,
            'test_id'=>$test_id,
        ]);
    }    

    protected function saveAnswers($dynamicModel, $test_id)
    {
        $answers = $dynamicModel->answer;
        
        if ($answers) {
            if ($test_id == Test::ZUNG_TEST_ID) {
                return $this->saveZungAnswer($answers);
            }
            
            return $this->saveTestUserAnswers($answers, $test_id);
        }
        
        return false;
    }
    
    protected function saveTestUserAnswers($answers, $test_id)
    {
        $session_id = $this->saveSession($test_id);
        
        if ($session_id) {
            foreach ($answers as $key=>$answer) {
                $model = new TestUserAnswer([
                    'session_id'=>$session_id,
                    'question_id'=>$key,
                    'answer_id'=>$answer
                ]);

                $model->save();
            }
            
            return $this->saveTestResult($session_id, $test_id);
        }
        
        return false;
    }


    protected function saveSession($test_id)
    {
        $user_id = Yii::$app->user->id;        
        $session = new TestUserSession(['user_id'=>$user_id, 'test_id'=>$test_id]);  
        
        if ($session->save()) {
            return $session->id;
        }        
        
        throw new ServerErrorHttpException('Возникла внутренняя ошибка сервера.');
    }

    protected function saveTestResult($session_id, $test_id)
    {
        $groups = TestGroup::findAll(['test_id'=>$test_id]);
        $test = Test::findOne($test_id);

        foreach ($groups as $group) {            
            $result = $this->getResult($session_id, $group->id);
            
            (new TestResult([
                'session_id'=>$session_id,
                'group_id'=>$group->id,
                'result'=>$result
            ]))->save();            
        }
        
        if ($test->testEmails) {
            $this->sendNotification($test_id);
        }
        
        return true;
    }

    private function getResult($session_id, $group_id)
    {
        $result = 0;
        $test_questions = TestQuestion::find()->select('id')->where(['group_id'=>$group_id])->all();
        
        foreach ($test_questions as $t_q) {
            $answer_id = TestUserAnswer::find()->select('answer_id')->where(['session_id'=>$session_id, 'question_id'=>$t_q->id])->scalar();
            $result += TestAnswer::find()->select('cost')->where(['id'=>$answer_id])->scalar();
        }
        
        return $result;
    }
    
    protected function saveZungAnswer($answers)
    {
        $user = Yii::$app->user->identity;
        
        if ($user instanceof Employee) {
            $user_phone = $user->phone ? : $user->phone_work;
        } else {
            $user_phone = $user->phone;
        }
        
        if ($user_phone) {
            $phone = AppHelper::localizePhone($user_phone);
            $this->saveZungPatient($phone, $user);
            $result = 0;
            
            $model = new ZungAnswers();
            $model->login = $phone;
            $model->date_time = date('Y-m-d H:i:s'); 
            
            foreach ($answers as $key=>$answer) {                
                $model->{'q' . $key} = $answer;
                $result += (int)$answer;
            }
            $model->result = (string)$result;
            
            return $model->save();
        }
        
        return false;
    }
    
    protected function saveZungPatient($phone, $user)
    {
        $fio = AppHelper::getFullNameAsArray($user->fullname);
        $patientExists = ZungPatients::find()->where(['phone'=>$phone])->exists();
        $model = ($patientExists) ? ZungPatients::findOne(['phone'=>$phone]) : new ZungPatients();
        $model->phone = $phone;
        $model->password = sha1(123654);
        $model->reg_date = date('Y-m-d');
        $model->surname = isset($fio['f']) ? $fio['f'] : '-';
        $model->name = isset($fio['i']) ? $fio['i'] : '-';
        $model->midname = isset($fio['o']) ? $fio['o'] : '-';
        $model->status = 'online-clinic';
        $model->save();
    }
    
    protected function sendNotification($test_id)
    {        
        $test = Test::findOne($test_id);
        $emails = $test->testEmails;        
        
        if ($emails) {
            $fullname = Yii::$app->user->identity->fullname;        
            $subject = 'Новое прохождение теста на сайте 0323.ru';
            $message = "Пользователь {$fullname} завершил прохождение теста {$test->name}.<br><br>" . 
                Html::a('Посмотреть результаты', Yii::$app->urlManager->createAbsoluteUrl(['/med/test-result']));
            
            foreach ($emails as $email) {
                Yii::$app->email->send($email->email, $subject, $message);
            }
        }
    }
}