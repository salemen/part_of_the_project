<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\test\TestGroup;
use app\models\test\search\TestGroup as TestGroupSearch;

class TestGroupController extends Controller
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
    
     public function actionIndex($test_id)
    {
        $searchModel = new TestGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $test_id);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'test_id'=>$test_id
        ]);
    }
    
    public function actionCreate($test_id)
    {
        $model = new TestGroup();
        $model->test_id = $test_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'test_id'=>$test_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'test_id'=>$model->test_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
       
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $test_id = $model->test_id;
        $model->delete();
        
        return $this->redirect(['index', 'test_id'=>$test_id]);
    }
    
    protected function findModel($id)
    {
        if (($model = TestGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}

