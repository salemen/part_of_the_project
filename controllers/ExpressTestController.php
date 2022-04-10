<?php
// Раздел "Экспресс-тесты"

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;

class ExpressTestController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
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
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionAmsler()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('vision_main', [
            'model'=>$model
        ]);
    }
    
    public function actionAstigmatism()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('vision_main', [
            'model'=>$model
        ]);
    }

    public function actionCharacter()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionColor()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('vision_main', [
            'model'=>$model
        ]);
    }
    
    public function actionDetail()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionHearing()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('hearing', [
            'model'=>$model
        ]);
    }
    
    public function actionOptpes()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionSides()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionVision()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('vision_main', [
            'model'=>$model
        ]);
    }
    
    public function actionWoman()
    {
        $model = new DynamicModel(['answer']);
        $model->addRule(['answer'], 'required');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
}