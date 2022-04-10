<?php
namespace app\modules\statistic\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{       
    public function actionIndex()
    {          
        return $this->render('index');
    }
}