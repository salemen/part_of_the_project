<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\anketa\AnketaRiskCategory;
use app\models\anketa\search\AnketaRiskCategory as AnketaRiskCategorySearch;

class AnketaRiskCategoryController extends Controller
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
        $searchModel = new AnketaRiskCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $anketa_id);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'anketa_id'=>$anketa_id
        ]);
    }
    
    public function actionCreate($anketa_id)
    {
        $model = new AnketaRiskCategory();
        $model->anketa_id = $anketa_id;
        
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
        $anketa_id = $model->anketa_id;
        $model->delete();
        
        return $this->redirect(['index', 'anketa_id'=>$anketa_id]);
    }
    
    protected function findModel($id)
    {
        if (($model = AnketaRiskCategory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}

