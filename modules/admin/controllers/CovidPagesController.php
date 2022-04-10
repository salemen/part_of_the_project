<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\covid\models\CovidPages;

class CovidPagesController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query'=>CovidPages::find(),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new CovidPages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($action, $controller)
    {
        $model = $this->findModel($action, $controller);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionDelete($action, $controller)
    {
        $this->findModel($action, $controller)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionToggleStatus($action, $controller)
    {
        $model = $this->findModel($action, $controller);
        $model->updateAttributes(['status'=>($model->status == 0) ? 10 : 0]);

        return $this->redirect(['index']);
    }  

    protected function findModel($action, $controller)
    {
        if (($model = CovidPages::findOne(['action'=>$action, 'controller'=>$controller])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}