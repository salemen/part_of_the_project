<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;

class DepartmentController extends Controller
{
    public function actionIndex($org_id)
    {        
        $org = Organization::findOne($org_id);
        $dataProvider = new ActiveDataProvider([
            'query'=>Department::find()->where(['org_id'=>$org_id])->orderBy('name'),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'org'=>$org
        ]);
    }
    
    public function actionCreate($org_id)
    {
        $model = new Department(['org_id'=>$org_id]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirect(['index', 'org_id'=>$model->org_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldDep = $model->name;        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
            if ($oldDep !== $model->name) {
                EmployeePosition::updateAll(['empl_dep'=>$model->name], ['empl_dep'=>$oldDep, 'org_id'=>$model->org_id]);
            }
            return $this->redirect(['index', 'org_id'=>$model->org_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model'=>$model
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}
