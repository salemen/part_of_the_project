<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\data\Organization;

class OrganizationController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>Organization::find()->where(['is_hidden'=>false])->orderBy(['is_santal'=>SORT_DESC, 'name'=>SORT_ASC]),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model'=>$model
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}
