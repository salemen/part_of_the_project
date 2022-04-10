<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\user\UserInterp;

class InterpController extends Controller
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
            'query'=>UserInterp::find()->where(['user_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
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
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect(['index']);
    }    
    
    protected function findModel($id)
    {
        if (($model = UserInterp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}