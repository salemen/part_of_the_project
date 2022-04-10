<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\anketa\AnketaRiskGroup;
use app\models\anketa\search\AnketaRiskGroup as AnketaRiskGroupSearch;

class AnketaRiskGroupController extends Controller
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
    
    public function actionIndex($category_id)
    {
        $searchModel = new AnketaRiskGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category_id);
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'category_id'=>$category_id
        ]);
    }
    
    public function actionCreate($category_id)
    {
        $model = new AnketaRiskGroup();
        $model->category_id = $category_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'category_id'=>$model->category_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'category_id'=>$model->category_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $ctegory_id = $model->category_id;
        $model->delete();
        
        return $this->redirect(['index', 'category_id'=>$ctegory_id]);
    }
    
    protected function findModel($id)
    {
        if (($model = AnketaRiskGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}

