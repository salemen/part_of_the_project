<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\cardio\Cardio;
use app\models\cardio\CardioResult;

class CardioController extends Controller
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
            'query'=>Cardio::find()->where(['patient_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionCardioDelete($id)
    {
        Yii::$app->response->format = 'json';
        $model = Cardio::findOne($id);
        
        return $model->delete();
    }
    
    public function actionView($id)
    {
        $model = CardioResult::findOne(['cardio_id'=>$id]);
        
        return $this->render('view', [
            'model'=>$model
        ]);
    }
}