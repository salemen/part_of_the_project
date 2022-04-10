<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\models\patient\Patient;
use app\models\patient\search\Patient as PatientSearch;

class PatientController extends Controller
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
                        'roles'=>['doctor']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PatientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['status'=>PatientSearch::STATUS_ACTIVE])->orderBy('fullname');

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model'=>$this->findModel($id),
        ]);
    }
    
    public function actionPasswordReset($id)
    {
        $password = sprintf("%06d", rand(1, 999999));
        
        $model = $this->findModel($id); 
        $model->setPassword($password);
        
        if ($model->save()) {   
            Yii::$app->sms->send($model->phone, $password . ' - новый пароль для входа на сайт 0323.ru');
            
            Yii::$app->session->setFlash('passReset', [
                'title'=>'Внимание!',
                'content'=>"Новый пароль: {$password}. Пациенту отправлено СМС с новым паролем.",
                'type'=>'green'
            ]);
            
            return $this->redirect(['index']);
        }
        
        throw new ServerErrorHttpException('');
    }        
    
    protected function findModel($id)
    {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}
