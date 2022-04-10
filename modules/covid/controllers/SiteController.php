<?php
namespace app\modules\covid\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'index';
        
        return $this->render('index');
    }
}