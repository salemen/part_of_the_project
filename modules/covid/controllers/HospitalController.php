<?php
namespace app\modules\covid\controllers;

use Yii;

class HospitalController extends BaseController
{
    public function actionIndex()
    {
        $model = $this->findFirstModel();
        
        return $this->redirect(['page', 'slug'=>$model->action]);
    }
    
    public function actionPage($slug)
    {
        $model = $this->findModel($slug);
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }       
}