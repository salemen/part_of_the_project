<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\anketa\Anketa;
use app\models\anketa\search\Anketa as AnketaSearch;

class AnketaController extends Controller
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
    
    public function actionIndex()
    {
        $searchModel = new AnketaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Anketa();
                
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->delete_file) { $this->deleteFile($model); }
            $model->save();
            
            return $this->redirect('index');
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->deleteFile($model);
        $model->delete();
        
        return $this->redirect(['index']);
    }
    
    public function actionToggleStatus($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['status'=>!$model->status]);

        return $this->redirect(['index']);
    }
    
    protected function findModel($id)
    {
        if (($model = Anketa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    private function deleteFile($model)
    {
        if ($model->file) {
            $file = Yii::getAlias('@webroot/uploads') . $model->file;
            
            if (file_exists($file)) { unlink($file); }
            
            $model->file = null;
        }
    }
}