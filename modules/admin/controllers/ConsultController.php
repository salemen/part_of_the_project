<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\models\consult\Consult;
use app\models\payments\Payments;
use app\models\consult\Consult as ConsultModel;
use app\models\consult\search\Consult as ConsultSearch;

class ConsultController extends Controller
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
        
        $searchModel = new ConsultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['is_payd'=>true])->orderBy(['created_at'=>SORT_DESC]);
        

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }    
    
    public function actionChangeEmployee($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->dep_id = null;
            if ($model->save()) {         
                return $this->redirect(['index']);
            } else { 
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            } 
        }
        
        return $this->renderAjax('change-employee', [
            'model'=>$model
        ]);
    }
    
    public function actionChangeEnd($id)
    {
        $model = $this->findModel($id);        
        $is_end = $model->is_end;
        
        $model->updateAttributes([
            'is_end'=>!$is_end,
            'ended_at'=>$is_end ? null : date('U')
        ]);
        
        return $this->redirect(['index']);
    }     
    
    public function actionCancel($id)
    {
        $model = $this->findModel($id);       
        $model->scenario = 'cancel-consult';
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {   
            $model->is_canceled = 1;
            if ($model->save()) {
                $payment = Payments::findOne([
                    'orderType'=>Payments::TYPE_CONSULT,
                    'orderNumber'=>$model->id
                ]);
                $payment->updateAttributes(['orderStatus'=>Payments::STATUS_RETURNED]);
                
                return $this->redirect(['index']);
            } else { 
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            } 
        }
        
        return ($model->is_canceled) ? $model->comment : $this->renderAjax('cancel', [
            'model'=>$model
        ]);
    }
    
    public function actionReadHistory($id)
    {
        $model = $this->findModel($id);
        
        return $this->renderAjax('read-history', [
            'model'=>$model
        ]);
    } 

    protected function findModel($id)
    {
        if (($model = Consult::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}