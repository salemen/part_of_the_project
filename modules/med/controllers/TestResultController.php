<?php
namespace app\modules\med\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\test\TestUserSession;
use app\models\test\search\TestUserSession as TestUserSessionSearch;

class TestResultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TestUserSessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['created_at'=>SORT_DESC]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query'=>$model->getTestUserAnswer(),
            'sort'=>false
        ]);
        
        return $this->render('view', [
            'dataProvider'=>$dataProvider,
            'model'=>$model
        ]);
    }
    
    public function actionUser($id)
    {
        return $this->renderAjax('user', [
            'model'=>$this->findUser($id),
        ]);
    }
    
    public function findModel($id)
    {
        if (($model = TestUserSession::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    protected function findUser($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }
        
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}