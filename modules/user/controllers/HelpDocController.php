<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\payments\Payments;
use app\models\user\UserData;
use app\modules\user\forms\PassChangeForm;

class HelpDocController extends Controller
{    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['*'],
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
        $user = Yii::$app->user;
        $model = Yii::$app->session->has('employee_santal') ? Employee::findOne($user->id) : Patient::findOne($user->id);
              
        return $this->render('index', [
            'model'=>$model
        ]); 
    }

    public function actionPacient()
    {
        $user = Yii::$app->user;
        $model = Yii::$app->session->has('employee_santal') ? Employee::findOne($user->id) : Patient::findOne($user->id);

        return $this->render('pacient', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($redirect = null)
    {        
        $user = Yii::$app->user;
        $model = Yii::$app->session->has('employee_santal') ? Employee::findOne($user->id) : Patient::findOne($user->id); 
        $pass = Yii::$app->session->has('employee_santal') ? false : new PassChangeForm($model);
        $user_data = UserData::findOne(['user_id'=>$user->id]) ? : new UserData(['user_id'=>$user->id]);
        
        if ($model instanceof Patient) {
            $model->scenario = 'edit';
            $fullname = AppHelper::getFullNameAsArray($model->fullname);
            $model->user_f = isset($fullname['f']) ? $fullname['f'] : null;
            $model->user_i = isset($fullname['i']) ? $fullname['i'] : null;
            $model->user_o = isset($fullname['o']) ? $fullname['o'] : null;
        }    
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('profileChangeSuccess', ['title'=>'Внимание!', 'content'=>'Данные учетной записи успешно сохранены!', 'type'=>'green']);
            return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
        }

        if ($pass && $pass->load(Yii::$app->request->post())) {
            if ($pass->changePassword()) {
                Yii::$app->session->setFlash('passwordChangeSuccess', ['title'=>'Внимание!', 'content'=>'Пароль успешно изменен!', 'type'=>'green']);
                return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($pass);
            }            
        }
        
        if ($user_data->load(Yii::$app->request->post()) && $user_data->save()) {
            Yii::$app->session->setFlash('userDataChangeSuccess', ['title'=>'Внимание!', 'content'=>'Данные успешно сохранены!', 'type'=>'green']);
            return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
        }
        
        return $this->render('_form', [
            'model'=>$model,
            'pass'=>$pass,
            'user_data'=>$user_data
        ]); 
    }
    
    public function actionProfit()
    {
        if (!Yii::$app->session->has('employee_santal')) {
            throw new ForbiddenHttpException('Вам запрещен просмотр данной страницы');
        }
        
        $query = Payments::find()
            ->joinWith(['employeeConsult'])
            ->where([
                'employee_id'=>Yii::$app->user->id,
                'orderStatus'=>Payments::STATUS_PAYD,
                'isTest'=>0
            ])
            ->orderBy(['orderCreatedDatetime'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>15
            ],
            'sort'=>false
        ]);
        $fullsum = $query->sum('shopSumAmount');
        $sum = ($fullsum == 0) ? 0 : round(($fullsum / 2), 2);
        
        return $this->render('profit', [
            'dataProvider'=>$dataProvider,
            'sum'=>$sum
        ]);
    }
}