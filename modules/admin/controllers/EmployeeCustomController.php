<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\employee\EmployeeCustom;

class EmployeeCustomController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'delete'=>['POST']
                ]
            ]
        ];
    }

    public function actionIndex($employee_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>EmployeeCustom::find()->where(['employee_id'=>$employee_id]),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'employee_id'=>$employee_id
        ]);
    }

    public function actionCreate($employee_id)
    {
        $model = new EmployeeCustom(['employee_id'=>$employee_id]);

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
        $model->delete();

        return $this->redirect(['index', 'employee_id'=>$model->employee_id]);
    }

    protected function findModel($id)
    {
        if (($model = EmployeeCustom::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}