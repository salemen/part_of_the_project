<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\anketa\AnketaPermission;
use app\models\anketa\search\AnketaPermission as AnketaPermissionSearch;

class AnketaPermissionController extends Controller
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
                        'roles'=>['admin']
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
    
    public function actionIndex($anketa_id)
    {
        $searchModel = new AnketaPermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $anketa_id);
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'anketa_id'=>$anketa_id,
        ]);
    }
    
    public function actionCreate($anketa_id)
    {
        $model = new AnketaPermission([
            'anketa_id'=>$anketa_id        
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        
        return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
    }
    
    public function actionGetType()
    {
        $type = Yii::$app->request->post('type');
        $model_id = Yii::$app->request->post('model_id');
        $model = ($model_id === null) ? new AnketaPermission() : $this->findModel($model_id);
        
        if ($type) {
           return $this->renderAjax('type', [
               'type'=>$type,
               'model'=>$model
           ]); 
        }
    }
    
    protected function findModel($id)
    {
        if (($model = AnketaPermission::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена');
    }
}