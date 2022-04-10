<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\payments\Payments;
use app\models\user\UserData;
use app\modules\user\forms\PassChangeForm;
use app\models\employee\EmployeeAdvisor;
use app\models\oms\Oms;
use app\models\data\Department;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
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
            'model' => $model
        ]);
    }

    public function actionUpdate($redirect = null)
    {
        $user = Yii::$app->user;
        $model = Yii::$app->session->has('employee_santal') ? Employee::findOne($user->id) : Patient::findOne($user->id);
        $pass = Yii::$app->session->has('employee_santal') ? false : new PassChangeForm($model);
        $user_data = UserData::findOne(['user_id' => $user->id]) ?: new UserData(['user_id' => $user->id]);
        $advisor = EmployeeAdvisor::findOne($model->id);

        if(isset($user_data) && $user_data->load(Yii::$app->request->post()) && $user_data->save()){
            Yii::$app->session->setFlash('profileChangeSuccess', ['title'=>'Внимание!', 'content'=>'Данные обновлены!', 'type'=>'green']);
        }
        if (isset($advisor) &&$advisor->load(Yii::$app->request->post()) && $advisor->save()){
        }

        if ($model instanceof Patient) {
            $model->scenario = 'edit';
            $fullname = AppHelper::getFullNameAsArray($model->fullname);
            $model->user_f = isset($fullname['f']) ? $fullname['f'] : null;
            $model->user_i = isset($fullname['i']) ? $fullname['i'] : null;
            $model->user_o = isset($fullname['o']) ? $fullname['o'] : null;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('profileChangeSuccess', ['title' => 'Внимание!', 'content' => 'Данные учетной записи успешно сохранены!', 'type' => 'green']);
            return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
        }

        if ($pass && $pass->load(Yii::$app->request->post())) {
            if ($pass->changePassword()) {
                Yii::$app->session->setFlash('passwordChangeSuccess', ['title' => 'Внимание!', 'content' => 'Пароль успешно изменен!', 'type' => 'green']);
                return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($pass);
            }
        }

        if ($user_data->load(Yii::$app->request->post()) && $user_data->save()) {
            Yii::$app->session->setFlash('userDataChangeSuccess', ['title' => 'Внимание!', 'content' => 'Данные успешно сохранены!', 'type' => 'green']);
            return ($redirect) ? $this->redirect($redirect) : $this->redirect(['update']);
        }


        $dep = Department::find()->where(['between', 'id', '1', '24'])->all();
        Yii::$app->params['items'] = ArrayHelper::map($dep,'name','name');
        Yii::$app->params['params'] = ['prompt'=>'Выберите поликлинику'];

        // получаем нужные организации
        $dep = Oms::find()->all();
        // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
        Yii::$app->params['items2'] = ArrayHelper::map($dep,'oms','oms');
        Yii::$app->params['params2'] = ['prompt'=>'Выберите организацию'];
        Yii::$app->params['user_data'] = $user_data;


        return $this->render('_form', [
            'model' => $model,
            'pass' => $pass,
            'user_data' => $user_data,
            'advisor' => $advisor,

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
                'employee_id' => Yii::$app->user->id,
                'orderStatus' => Payments::STATUS_PAYD,
                'isTest' => 0
            ])
            ->orderBy(['orderCreatedDatetime' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15
            ],
            'sort' => false
        ]);
        $fullsum = $query->sum('shopSumAmount');
        $sum = ($fullsum == 0) ? 0 : round(($fullsum / 2), 2);

        return $this->render('profit', [
            'dataProvider' => $dataProvider,
            'sum' => $sum
        ]);
    }
}