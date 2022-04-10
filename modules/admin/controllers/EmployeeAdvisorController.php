<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\Employee;
use app\models\employee\EmployeeAdvisor;
use app\models\employee\search\EmployeeAdvisorAdmin as EmployeeAdvisorSearch;

class EmployeeAdvisorController extends Controller
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
                        'roles'=>['manager']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'delete'=>['POST']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new EmployeeAdvisorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['employee.status'=>Employee::STATUS_ACTIVE])
            ->orderBy(['status'=>SORT_DESC, 'position'=>SORT_DESC]);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }


            $model1 = EmployeeAdvisor::findOne($id);


        return $this->render('_form', [
            'model'=>$model,
            'model1'=>$model1
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }         

    protected function findModel($id)
    {
        if (($model = EmployeeAdvisor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}