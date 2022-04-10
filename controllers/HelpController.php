<?php
// Ajax получение информации

namespace app\controllers;

use Yii;
use yii\web\Controller;

class HelpController extends Controller
{    
    public function actionLimfo()
    {
        return $this->renderAjax('limfo');
    }  
    
    public function actionChastDih()
    {
        return $this->renderAjax('chast-dih');
    }
    public function actionOdishka()
    {
        return $this->renderAjax('odishka');
    }
    public function actionKashel()
    {
        return $this->renderAjax('kashel');
    }
}