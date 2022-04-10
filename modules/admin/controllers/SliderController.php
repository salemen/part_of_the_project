<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use app\models\other\Slider;

class SliderController extends Controller
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
            'query'=>Slider::find()->orderBy('position'),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Slider();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionSort()
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>Slider::find()->orderBy('position'),
            'pagination'=>false,
            'sort'=>false
        ]);
        
        $post = Yii::$app->request->post('data');
        if ($post) { 
            foreach ($post as $pos=>$id) { 
                $this->findModel($id)->updateAttributes(['position'=>$pos]);
            }
            return $this->redirect(['index']);            
        }
        
        return $this->render('sort', [
            'dataProvider'=>$dataProvider
        ]);
    } 

    protected function findModel($id)
    {
        if (($model = Slider::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}