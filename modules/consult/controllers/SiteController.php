<?php
namespace app\modules\consult\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use app\helpers\CryptHelper;
use app\models\consult\Consult;
use app\models\consult\ConsultHistory;
use app\models\cron\CronNotification;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\payments\Payments;
use app\modules\consult\forms\ChatForm;

class SiteController extends Controller
{    
    private $crypter = null;
    
    public function __construct($id, $module, $config = array())
    {
        $this->crypter = new CryptHelper(Yii::$app->params['cryptKey']);
        
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex($id = null)
    {
        $consult = $this->findModel($id);

        $user = Yii::$app->user->identity;
        
        if ($consult) {
            $this->checkConsult($consult->id, $user);
            $formModel = new ChatForm();
            $this->saveBotMessage($consult);
            $messages = ConsultHistory::findAll(['consult_id'=>$consult->id]);
            ConsultHistory::updateAll(['is_read'=>true], "consult_id = {$consult->id} AND message_by != '{$user->id}' AND is_read = 0");

            return $this->render('index', [
                'consult'=>$consult,
                'crypter'=>$this->crypter,
                'formModel'=>$formModel,
                'messages'=>$messages,
                'user'=>$user
            ]);
        }

        Yii::$app->session->setFlash('emptyConsults', [
            'title'=>'Внимание!',
            'content'=>'У вас нет активных консультаций.',
            'type'=>'orange'
        ]);
        
        return $this->redirect(['/site/index']);
    }
    
    public function actionDepartment($id)
    {
        $model = Consult::findOne($id);
        $model->scenario = 'choose-department';
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id'=>$id]);
        }

        return $this->render('department', [
            'model'=>$model
        ]);
    } 
    
    public function actionPayment($id)
    {
        $model = Consult::findOne($id);
        
        if (!Consult::isConsultNotPayd($id)) {
            return $this->redirect(['index', 'id'=>$id]);
        }
            
        if (!Yii::$app->params['specialConsult']['active'] && $model->is_special) {
            $model->updateAttributes(['is_special'=>false]);
        }

        return $this->render('payment', [
            'model'=>$model
        ]);
    }
    
    public function actionVideo($id)
    {
        $model = $this->findModel($id);
        
        if (!Consult::isConsultAllowed($id)) {
            throw new ForbiddenHttpException('Вам не разрешено просматривать данную страницу.');
        }
        
        return $this->render('video', [
            'model'=>$model
        ]);
    } 
    
    public function actionConsultCancel()
    {
        $consult_id = Yii::$app->request->post('consult_id');
        
        if (Yii::$app->request->isAjax && $consult_id) {
            $consult = Consult::findOne($consult_id);
            if ($consult->delete()) {
                return $this->redirect(['index']);
            }
            
            throw new ServerErrorHttpException('Не удалось отменить консультацию.');
        }
    }
    
    public function actionConsultHide()
    {
        $consult_id = Yii::$app->request->post('consult_id');
        
        if (Yii::$app->request->isAjax && $consult_id) {
            $consult = Consult::findOne($consult_id);
            $user = Yii::$app->user;
            if ($consult->employee_id == $user->id) {
                $consult->e_hide = 1;
            }
            if ($consult->patient_id == $user->id) {
                $consult->p_hide = 1;
            }            
            if ($consult->save()) {
                return $this->redirect(['index']);
            }
            
            throw new ServerErrorHttpException('Не удалось скрыть консультацию.');
        }
    }
    
    public function actionConsultEnd()
    {
        $consult_id = Yii::$app->request->post('consult_id');
        
        if (Yii::$app->request->isAjax && $consult_id) {
            $model = Consult::findOne($consult_id);
            $model->is_end = 1;
            $model->ended_at = date('U');
            
            if ($model->save()) {
                ConsultHistory::updateAll(['is_read'=>true], "consult_id = {$consult_id}");
                
                return true;
            }
            
            return false;
        }
    }
    
    public function actionConsultPay()
    {
        $consult_id = Yii::$app->request->post('consult_id');
        $employee_id = Yii::$app->request->post('employee_id');
        $is_special = Yii::$app->request->post('is_special') ? 1 : 0;
        
        if (Yii::$app->request->isAjax) {
            if ($consult_id) {
                $consult = Consult::findOne($consult_id);
            }
            if ($employee_id) {
                $employee = Employee::findOne(['id'=>$employee_id]);
                $consult = new Consult([ 
                    'employee_id'=>$employee->id,
                    'patient_id'=>Yii::$app->user->id,
                    'is_special'=>$is_special
                ]);
            }
            if ($consult->save()) {
                $cost = Consult::getConsultCost($consult->employee->advisor);
                if (Yii::$app->params['specialConsult']['active'] && $consult->is_special) {
                    $cost = Yii::$app->params['specialConsult']['cost'];
                }
                
                if ($cost === 0) {
                    $this->allowConsult($consult);
                } else {
                    $ym_merchant_receipt = ['customerContact'=>Patient::patientPhone(), 'items'=>[['quantity'=>1, 
                    'price'=>['amount'=>$cost . '.00'], 'tax'=>1, 'text'=>'Онлайн консультация']]];

                    $ymmerchantreceipt = json_encode($ym_merchant_receipt, JSON_UNESCAPED_UNICODE);

                    $params = [
                        'customerNumber'=>$consult->patient_id,   
                        'serviceType'=>Payments::TYPE_CONSULT,
                        'serviceNumber'=>$consult->id,                          
                        'sum'=>$cost . '.00',
                        'ym_merchant_receipt'=>$ymmerchantreceipt
                    ];
                    return Yii::$app->yakassa->payment($params);
                }                
            } else {
                return false;
            }
        }
    }
    
    protected function allowConsult($model)
    {
        if ($model->updateAttributes(['is_payd'=>true])) {
            $this->saveCronNotification($model->id, CronNotification::TYPE_NEW_CONSULT);
            
            return $this->redirect(['index', 'id'=>$model->id]);
        }
    }
    
    protected function checkConsult($consult_id, $user)
    {
        $consult = Consult::findOne($consult_id);
        
        if ($consult->employee_id == $user->id && Consult::isDepNotExists($consult_id)) {
            return $this->redirect(['department', 'id'=>$consult_id]);
        } elseif ($consult->patient_id == $user->id) {
            if ($user instanceof Patient && !Patient::isProfileValid($user->id)) {
                Yii::$app->session->setFlash('profileUpdate', [
                    'title'=>'Внимание!',
                    'content'=>'Пожалуйста, заполните данные учетной записи полностью.',
                    'type'=>'orange'
                ]);

                return $this->redirect(['/user/profile/update', 'redirect'=>Url::current()]);
            }
            if (Consult::isConsultNotPayd($consult_id)) {
                return $this->redirect(['payment', 'id'=>$consult_id]);
            }
        } elseif (!Consult::isConsultAllowed($consult_id)) {
            throw new ForbiddenHttpException('Вам не разрешено просматривать данную страницу.');
        } else {
            return;
        }
    }      

    protected function findModel($id = null)
    {       
        return ($id !== null) ? Consult::findOne($id) : Consult::getConsults(true);
    }
    
    protected function saveCronNotification($target, $type)
    {
        (new CronNotification([
            'target'=>(string)$target,
            'message'=>null,
            'type'=>$type
        ]))->save();
    }
    
    protected function saveBotMessage($consult)
    {
        if (!$consult->is_end) {
            $messageExists = ConsultHistory::find()->where(['consult_id'=>$consult->id])->exists();


            if(isset($consult->employee)){
                $employeeName= $consult->employee->fullname;
            } else {
                $employeeName = 'Сотрудник удален';
            }

            if (!$messageExists && $consult->employee_id !== '2b451376-245d-11e4-8765-c81f66e67cd7') {
                Yii::$app->db->createCommand()
                    ->batchInsert(
                        'consult_history',
                        ['consult_id', 'message', 'message_by', 'message_type'],
                        [
                            [
                                $consult->id,
                                $this->crypter->encrypt("Здравствуйте!! Вы присоединились к онлайн-консультации врача. {$employeeName} ответит Вам в ближайшее время."),
                                $consult->employee_id,
                                ConsultHistory::TYPE_BOT
                            ],
                            [
                                $consult->id,
                                $this->crypter->encrypt("Сообщите пожалуйста, что Вас беспокоит?<br>Вы можете прикрепить медицинские документы и результаты анализов нажав на скрепку в левом нижнем углу чат-консультации."),
                                $consult->employee_id,
                                ConsultHistory::TYPE_BOT
                            ]
                        ])
                    ->execute();
            }
        }
    }        
}