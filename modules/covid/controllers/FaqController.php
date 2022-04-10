<?php
namespace app\modules\covid\controllers;

class FaqController extends BaseController
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