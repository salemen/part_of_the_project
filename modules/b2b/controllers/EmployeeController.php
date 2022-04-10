<?php
namespace app\modules\b2b\controllers;

use app\models\cron\CronNotification;
use Yii;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\b2b\forms\EmployeeSignupForm;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;

class EmployeeController extends Controller
{
    public function actionIndex()
    {
        $orgIds = EmployeePosition::getOrgIds();
        $dataProvider = new ActiveDataProvider([
            'query' => Employee::find()
                ->joinWith(['roles', 'positions'])
                ->where(['IN', 'org_id', $orgIds])
                ->orderBy('fullname')
                ->distinct(),
            'sort' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new EmployeeSignupForm();


        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['index']);
        }

        return $this->render('_create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'edit';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
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
            'pagination' => false,
            'sort' => false
        ]);

        return $this->render('view', [
            'model' => $model,
            'posProvider' => $posProvider
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }


    // --------- удаление сотрудника ----------
    public function actionDelete($id)
    {
        //notification delete if exist
        $cronNotifications = CronNotification::find($id)->all();
        foreach ($cronNotifications as $cronNotification) {
            $cronNotification->delete();
        }

        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }
}
