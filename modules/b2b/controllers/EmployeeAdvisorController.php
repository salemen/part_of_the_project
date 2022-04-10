<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\EmployeeAdvisor;
use app\models\employee\EmployeePosition;
use app\models\employee\search\EmployeeAdvisorAdmin as EmployeeAdvisorSearch;

class EmployeeAdvisorController extends Controller
{
    public function actionIndex()
    {
        $orgIds = EmployeePosition::getOrgIds();
        $searchModel = new EmployeeAdvisorSearch();
        $dataProvider = $searchModel->searchCorp(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['IN', 'org_id', $orgIds])
            ->orderBy(['status'=>SORT_DESC, 'position'=>SORT_DESC])
            ->distinct();

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionCreate()
    {
        $model = new EmployeeAdvisor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
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