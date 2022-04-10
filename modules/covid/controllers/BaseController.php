<?php
namespace app\modules\covid\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\covid\models\CovidPages;

class BaseController extends Controller
{
    protected function findFirstModel()
    {
        if (($model = CovidPages::find()->where(['controller'=>$this->id, 'status'=>CovidPages::STATUS_ACTIVE])->orderBy('name')->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    protected function findModel($action)
    {        
        if (($model = CovidPages::findOne(['action'=>$action, 'controller'=>$this->id, 'status'=>CovidPages::STATUS_ACTIVE])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}