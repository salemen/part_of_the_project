<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\EmployeeAdvisor;
use app\models\employee\search\EmployeeAdvisorAdmin as EmployeeAdvisorSearch;

class SeoController extends Controller
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
                        'roles'=>['seo']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new EmployeeAdvisorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([EmployeeAdvisor::tableName() . '.status'=>EmployeeAdvisor::STATUS_ACTIVE])->orderBy('position');

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

        return $this->render('_form', [
            'model'=>$model
        ]);
    }        

    protected function findModel($id)
    {
        if (($model = EmployeeAdvisor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    } 
}