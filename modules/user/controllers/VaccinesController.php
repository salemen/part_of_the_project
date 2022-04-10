<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\models\user\UserVaccines;

class VaccinesController extends Controller
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
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>UserVaccines::find()->where(['patient_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->renderAjax('view', [
            'model'=>$model
        ]);
    } 
    
    public function actionCreate()
    {
        $model = new UserVaccines(['patient_id'=>Yii::$app->user->id]); 
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {         
                if ($model->save()) {         
                    return $this->redirect(['index']);
                } else { 
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }   
            
            return $this->renderAjax('_form', [
                'model'=>$model
            ]);
        }
        
        return $this->redirect(['index']);
    }   
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {         
                if ($model->save()) {         
                    return $this->redirect(['index']);
                } else { 
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }   
            
            return $this->renderAjax('_form', [
                'model'=>$model
            ]);
        }
        
        return $this->redirect(['index']);
    } 
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect(['index']);
    }       
    
    protected function findModel($id)
    {
        if (($model = UserVaccines::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}