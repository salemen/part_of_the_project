<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\web\Controller;
use app\models\data\Organization;
use app\modules\b2b\forms\EmployeeImportForm;



class EmployeeImportController extends Controller
{
    public function actionXlsx()
    {
        $user = Yii::$app->user;
        $model = new EmployeeImportForm();

        $orgArray = Organization::find()->joinWith('positions', true, 'INNER JOIN')->where(['employee_id' => $user->id])->orderBy('name')->all();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('registerSuccess', [
                'title' => 'Внимание!',
                'content' => 'Пользователи добавлены.',
                'type' => 'green'
            ]);

            return $this->redirect(['/b2b/employee']);
        }

        return $this->render('xlsx', [
            'model' => $model,
            'orgArray' => $orgArray
        ]);
    }
}
