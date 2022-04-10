<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;

class EmployeePositionController extends Controller
{
    public function actionIndex($employee_id)
    {        
        $employee = Employee::findOne($employee_id);
        $dataProvider = new ActiveDataProvider([
            'query'=>EmployeePosition::find()->where(['employee_id'=>$employee_id]),
            'pagination'=>false,
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'employee'=>$employee
        ]);
    }
    
    public function actionCreate($employee_id)
    {
        $model = new EmployeePosition([
            'id'=>Yii::$app->security->generateRandomString(16),
            'employee_id'=>$employee_id,
            'type'=>'Основное место работы',
            'status'=>10,
            'is_doctor'=>1,
            'is_santal'=>0
        ]);
        $model->scenario = 'edit';
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {   
            return $this->redirect(['index', 'employee_id'=>$model->employee_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }        
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'edit';
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {   
            return $this->redirect(['index', 'employee_id'=>$model->employee_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (EmployeePosition::find()->where(['employee_id'=>$model->employee_id])->count() > 1) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('positionRequired', ['title'=>'Внимание!', 'content'=>'Должно быть указано хотя бы одно место работы.', 'type'=>'red']);
        }
        
        return $this->redirect(['index', 'employee_id'=>$model->employee_id]);        
    }        
    
    protected function findModel($id)
    {
        if (($model = EmployeePosition::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}