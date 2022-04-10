<?php
namespace app\modules\covid\controllers;

use yii\web\Controller;
use app\modules\covid\models\HotlineArea;

class HotlineController extends Controller
{
    public function actionIndex()
    {
        $model = HotlineArea::find()->all();
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }
}