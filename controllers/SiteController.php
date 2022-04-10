<?php
// Главная страница, формы авторизации, регистрации и прочее

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use app\helpers\AppHelper;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\widgets\ActiveForm;
use app\actions\AppParams;
use app\actions\MediaUpload;
use app\forms\AuthForm;
use app\forms\LoginForm;
use app\forms\OrgSignupForm;
use app\forms\PassResetForm;
use app\forms\PassSetForm;
use app\forms\SaveToProfileForm;
use app\forms\SignupForm;
use app\models\employee\Employee;
use app\models\menu\MenuSection;
use app\models\other\Slider;
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
use yii\base\DynamicModel;
use app\models\Patient\Patient;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
            'app-params'=>[
                'class'=>AppParams::className()
            ],
            'error'=>[
                'class'=>ErrorAction::className(),
                'layout'=>'error'
            ],
            'fileapi-upload'=>[
                'class'=>FileAPIUpload::className(),
                'path'=>'temp',
                'uploadOnlyImage'=>false
            ],  
            'upload-checker'=>[
                'class'=>MediaUpload::className(),
                'csrfValidation'=>false,
                'path'=>'storage/checker',
                'resultName'=>'location',
                'saveWebp'=>false
            ],
            'upload-media'=>[
                'class'=>MediaUpload::className(),
                'csrfValidation'=>false,
                'path'=>'uploads',
                'resultName'=>'location',
                'saveWebp'=>false
            ]            
        ];
    }

    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['logout'],
                'rules'=>[
                    [
                        'allow'=>true,       
                        'actions'=>['logout'],
                        'roles'=>['@']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'logout'=>['post']
                ]
            ]
        ];
    }
 
    public function actionIndex()
    {
        $itemFavorite = MenuSection::findOne(['slug'=>'favorite', 'status'=>10]);
        $itemHealth = MenuSection::findOne(['slug'=>'health', 'status'=>10]);
        $itemServices = MenuSection::findOne(['slug'=>'medservice', 'status'=>10]);        
        $slider = Slider::find()->where(['show_main'=>true])->orderBy('position')->all();        
        
        return $this->render('index', [
            'itemFavorite'=>$itemFavorite,
            'itemHealth'=>$itemHealth,
            'itemServices'=>$itemServices,
            'slider'=>$slider
        ]);
    }

    public function actionPatientHelp()
    {

        return $this->render('/help/patient');
    }

    //////////// начало теста ///////////////

    public function actionTesting($test_id)
    {
        $model = Test::findOne($test_id);

        return $this->render('/test/index', [
            'model'=>$model
        ]);
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

        return $this->render('/test/test', [
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
        $model->status = 10;
        $model->save();
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

    protected function sendNotification($test_id)
    {
        $test = Test::findOne($test_id);
        $emails = $test->testEmails;

        if ($emails) {
            $fullname = Yii::$app->user->identity->fullname;
            $subject = 'Новое прохождение теста на сайте 0323.ru';
            $message = "Пользователь {$fullname} завершил прохождение теста {$test->name}.<br><br>" .
                Html::a('Посмотреть результаты', Yii::$app->urlManager->createAbsoluteUrl(['/modules/med/test-result']));

            foreach ($emails as $email) {
                Yii::$app->email->send($email->email, $subject, $message);
            }
        }
    }

    ////////// конец теста /////////////

    public function actionAuth($key)
    {
        $model = new AuthForm(['auth_key'=>$key]);
        $user = Yii::$app->user;
        
        if (!$user->isGuest) {
            $user->logout();
            return $this->goHome();
        }
        
        if ($model->login()) {
            if ($user->identity instanceof Employee) {
                Yii::$app->session->setFlash('authKeyChanged', [
                    'title'=>'Ключ авторизации изменен.',
                    'content'=>'В будущем, для входа на сайт, используйте свои корпоративные данные сотрудника.',
                    'type'=>'orange'
                ]);
                return $this->goHome();
            } else {
                return $this->redirect(['pass-set']);
            }            
        }

        return $this->goHome();
    }
    
    public function actionCollapseSidebar()
    {
        $session = Yii::$app->session;
        
        if ($session->has('sidebar-collpsed')) {
            $session->remove('sidebar-collpsed');
        } else {
            $session->set('sidebar-collpsed', true);
        }
    } 

    public function actionLogin($redirect = false)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->redirect(Yii::$app->request->referrer);
        }

        $this->layout = 'form';
        return $this->render('_static/login', [
            'model'=>$model
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }    
    
    public function actionOnwork()
    {
        $this->layout = 'error';
        
        return $this->render('onwork');
    }  
    
    public function actionOrgLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $this->layout = 'form';
        return $this->render('_static/org-login', [
            'model'=>$model
        ]);
    }
    
    public function actionOrgSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new OrgSignupForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['/b2b/site/index']);
        }

        // if(isset($_POST['OrgSignupForm']))
        // {
        //     var_dump($_POST['OrgSignupForm']); die();
        // }

        // echo $model->org_inn;

        $db = mysql_connect("localhost", "new.statprivat.ru", "IomDb29pnA5KdSsc");
        mysql_select_db("new.statprivat.ru", $db);
        mysql_query("set names utf8");

        $this->layout = 'form';
        return $this->render('_static/org-signup', [
            'model'=>$model, 'db'=>$db
        ]);
    }

    public function actionOrgActivate()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $this->layout = 'form';
        return $this->render('_static/org-activate', [
            'model'=>$model
        ]);
    }

    public function actionOrgData()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new OrgSignupForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['/b2b/site/index']);
        }

        $db = mysql_connect("localhost", "new.statprivat.ru", "IomDb29pnA5KdSsc");
        mysql_select_db("new.statprivat.ru", $db);
        mysql_query("set names utf8"); 


        $this->layout = 'form';
        return $this->render('_static/org-data', [
            'model'=>$model, 'db'=>$db
        ]);
    }
    
    public function actionPassSet()
    {
        $user = Yii::$app->user;
        
        if ($user->isGuest || $user->identity instanceof Employee) {
            return $this->goHome();
        }
        
        $model = new PassSetForm();
        
        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('passSet', [
                    'title'=>'Внимание!',
                    'content'=>'Пароль успешно сохранен.',
                    'type'=>'green'
                ]);
                return $this->redirect(['/user/profile']);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }
        }

        $this->layout = 'form';
        return $this->render('_static/pass-set', [
            'model'=>$model
        ]);
    }

    public function actionPassReset()
    {
        $model = new PassResetForm();

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->sendSms()) {
                    Yii::$app->session->setFlash('passReset', [
                        'title'=>'Внимание!',
                        'content'=>'На указанный номер телефона отправлено СМС с новым паролем.',
                        'type'=>'orange'
                    ]);
                    return $this->goHome();
                } else {
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }

            return $this->renderAjax('_ajax/pass-reset', [
                'model'=>$model
            ]);
        }

        $this->layout = 'form';
        return $this->render('_static/pass-reset', [
            'model'=>$model
        ]);
    }
        
    public function actionSignup($redirect = false)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new SignupForm();

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        $this->notification('consult');
                        return ($redirect) ? $this->redirect($redirect) : $this->goHome();
                    }
                } else {
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }

            return $this->renderAjax('_ajax/signup', [
                'model'=>$model
            ]);
        }

        $this->layout = 'form';
        return $this->render('_static/signup', [
            'model'=>$model
        ]);
    }
    
    public function actionSaveToProfile($redirect = false)
    {
        $model = new SaveToProfileForm();        
        $params = Yii::$app->request->post('params');
        
        $user = Yii::$app->user;

        if (!$user->isGuest) {
            $model->email = $user->identity->email;
            $model->phone = $user->identity->phone;
        }
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('passReset', [
                        'title'=>'Внимание!',
                        'content'=>'Данные успешно сохранены.',
                        'type'=>'green'
                    ]);
                    return ($redirect) ? $this->redirect($redirect) : $this->goHome();
                } else {
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }
            
            return $this->renderAjax('_ajax/save-to-profile', [
                'model'=>$model,
                'params'=>$params
            ]);
        }
        
        return $this->goHome();
    }
    
    protected function notification($key)
    {
        switch ($key) {
            case 'consult':
                $allow = !Yii::$app->session->has('employee_santal');
                $content = 'Онлайн-консультации проводятся на платформе нашего сайта (в разделе "Мои консультации").';
                $title = 'Напоминаем!';
                $type = 'orange';
                break;
        }
        
        if ($allow) {
            Yii::$app->session->setFlash($key, [
                'content'=>$content,
                'title'=>$title,            
                'type'=>$type
            ]);
        }
    }

}