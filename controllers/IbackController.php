<?php
// Раздел "Датчик осанки iBack"

namespace app\controllers;

use Yii;
use yii\web\Controller;

class IbackController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($url)
    {
        return $this->renderAjax('view', [
            'url'=>$url
        ]);
    }
}
