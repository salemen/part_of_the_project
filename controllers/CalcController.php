<?php
// Раздел "Расчет факторов риска" (оценка здоровья)

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;

class CalcController extends Controller
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
        $model = new DynamicModel(['height', 'weight']);
        $model->addRule(['height', 'weight'], 'required');
        $model->addRule(['height', 'weight'], 'integer');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionBrok()
    {
        $model = new DynamicModel(['sex', 'height']);
        $model->addRule(['sex', 'height'], 'required');
        $model->addRule(['height'], 'integer');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionCalory()
    {
        $model = new DynamicModel(['activity', 'sex', 'height']);
        $model->addRule(['activity', 'sex', 'height'], 'required');
        $model->addRule(['height'], 'integer');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
    
    public function actionSmoky()
    {
        $model = new DynamicModel(['cigars', 'experience']);
        $model->addRule(['cigars', 'experience'], 'required');
        $model->addRule(['cigars', 'experience'], 'integer');
        
        return $this->render('_main', [
            'model'=>$model
        ]);
    }
}