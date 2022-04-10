<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\admin\forms\EmployeeSignupForm;
use app\models\employee\Employee;
use app\models\employee\EmployeeAuth;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeeDocumentOther;
use app\models\employee\EmployeePosition;
use app\models\employee\search\Employee as EmployeeSearch;

class EmployeeController extends Controller
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
                        'roles' => ['doctor']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $is_santal = true;
        $is_official = true;
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'is_santal' => $is_santal,
            'is_official' => $is_official,
            'status' => EmployeeSearch::STATUS_ACTIVE
        ])->orderBy('fullname');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_santal' => $is_santal,
            'is_official' => $is_official
        ]);
    }

    public function actionIndex2()
    {
        $is_santal = true;
        $is_official = false;
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'is_santal' => $is_santal,
            'is_official' => $is_official
        ])->orderBy('fullname');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_santal' => $is_santal,
            'is_official' => $is_official
        ]);
    }

    public function actionIndex3()
    {
        $is_santal = false;
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'is_santal' => $is_santal,
            'status' => EmployeeSearch::STATUS_ACTIVE
        ])->orderBy('fullname');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_santal' => $is_santal,
            'is_official' => true
        ]);
    }

    public function actionCreate()
    {
        $model = new EmployeeSignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['index2']);
        }

        return $this->render('_create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->password) {
                $auth = EmployeeAuth::findOne(['user_id' => $model->id]) ?: new EmployeeAuth(['user_id' => $model->id]);
                $auth->setPassword($model->password);
                $auth->save();
            }

            return $this->redirect(['index2']);
        }

        return $this->render('_update', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $posProvider = new ActiveDataProvider([
            'query' => EmployeePosition::find()->where(['employee_id' => $id]),
            'sort' => false
        ]);
        $qualProvider = new ActiveDataProvider([
            'query' => EmployeeDocument::find()
                ->select(['employee_id', 'empl_qual', 'empl_spec'])
                ->where(['doc_type' => 'Сертификат', 'employee_id' => $id])
                ->distinct('empl_qual'),
            'sort' => false
        ]);
        $docProvider = new ActiveDataProvider([
            'query' => EmployeeDocumentOther::find()
                ->select(['employee_id', 'empl_spec', 'doc_type'])
                ->where(['employee_id' => $id])
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

    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}
