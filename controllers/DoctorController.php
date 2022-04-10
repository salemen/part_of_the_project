<?php
// Раздел "Консультации"

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeeDocumentOther;
use app\models\employee\EmployeePosition;
use app\models\employee\search\EmployeeAdvisor as EmployeeAdvisorSearch;

class DoctorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['consult-details'],
                'rules' => [
                    [
                        'actions' => ['consult-details'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user;
                            if ($user->isGuest) {
                                return false;
                            }
                            return true;
                        }
                    ]
                ]
            ]
        ];
    }

    // Перечень консультантов
    public function actionIndex()
    {
        $searchModel = new EmployeeAdvisorSearch();
        $dataProvider = $searchModel->searchcon(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['employee.status' => 10])
            ->orderBy(['position' => SORT_DESC])
            ->distinct();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_part/filter', [
                'model' => $searchModel
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'model' => $dataProvider->getModels(),
            'pagination' => $dataProvider->pagination
        ]);
    }

    //регистрация врача
    public function actionRegistration()
    {
        $model = new Employee();
        Yii::$app->session->setFlash('success',"<center>Благодарим за регистрацию! Наш менеджер свяжется с Вами в ближайшее время.</center>");

        
        if ($model->load(Yii::$app->request->post())) {

            die('stop');
            if($model->validateEmail($model->email) && $model->validatePhone($model->phone)) {
                $model->save();
                Yii::$app->session->setFlash('success',"<center>Благодарим за регистрацию! Наш менеджер свяжется с Вами в ближайшее время.</center>");
              //  Yii::$app->mailer->compose('ads', ['tel' => $model->phone] )->setFrom ([Yii::$app->params['webManagerEmail']=>'Новая регистрация врача на сайте'])-> setTo(array(Yii::$app->params['webManagerEmail'] => 'NAME',Yii::$app->params['managerEmail'] => 'NAME2'))-> setSubject('Новая регистрация врача на сайте')->send ();
            }
        } else {
            $model->generateId();
        }

        return $this->render('registration', [
            'model' => $model
        ]);
    }
    
    // Расширенная информация о консультанте
    public function actionView($id)
    {
        if (($model = $this->findModelByFullname($id)) !== null) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model = $this->findModels($id);
        $posProvider = new ActiveDataProvider([
            'query' => EmployeePosition::find()->where(['employee_id' => $model->id]),
            'sort' => false
        ]);
        $qualProvider = new ActiveDataProvider([
            'query' => EmployeeDocument::find()
                ->select(['employee_id', 'empl_qual', 'empl_spec'])
                ->where(['doc_type' => 'Сертификат', 'employee_id' => $model->id])
                ->distinct('empl_qual'),
            'sort' => false
        ]);
        $docProvider = new ActiveDataProvider([
            'query' => EmployeeDocumentOther::find()
                ->select(['employee_id', 'empl_spec', 'doc_type'])
                ->where(['employee_id' => $model->id])
                ->distinct('empl_qual'),
            'sort' => false
        ]);

        return $this->render('view', [
            'model' => $model,
            'posProvider' => $posProvider,
            'qualProvider' => $qualProvider,
            'docProvider' => $docProvider
        ]);
    }

    // Информация о консультации и переход к оплете
    public function actionConsultDetails($id)
    {
        $model = $this->findModels($id);

        if (Consult::isConsultExist($model->id, Yii::$app->user->id)) {
            Yii::$app->session->setFlash('consultExist', [
                'title' => 'Внимание!',
                'content' => 'Консультация с данным специалистом уже существует. Пожалуйста, оплатите/отмените или завершите консультацию',
                'type' => 'orange'
            ]);
            return $this->redirect(['/consult']);
        }

        return $this->renderAjax('_part/consult-details', [
            'model' => $model
        ]);
    }

    public function actionConsultNone($id)
    {
        $model = $this->findModels($id);

        return $this->renderAjax('_part/consult-none', [
            'model' => $model
        ]);
    }

    protected function findModels($id)
    {
        if (($model = Employee::find()->joinWith(['advisors'])->where(['employee.id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function findModel($id)
    {
        if (($model = Employee::find()->joinWith(['advisor', 'positionsActive'])->where(['employee.id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function findModelByFullname($fullname)
    {
        if (($model = Employee::findOne(['fullname' => trim($fullname)])) !== null) {
            return $model;
        }

        return null;
    }
}