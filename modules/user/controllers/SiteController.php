<?php
namespace app\modules\user\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{       
    public function actionIndex()
    {        
        return $this->redirect('/user/profile');
    }
}