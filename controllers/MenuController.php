<?php
// Отображение меню (разделы и подразделы)

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\menu\MenuSection;

class MenuController extends Controller
{    
    public function actionIndex($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = MenuSection::findOne(['slug'=>$id, 'status'=>10])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}