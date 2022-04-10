<?php
namespace app\modules\api\controllers;

use Yii;
use yii\data\ArrayDataProvider;

use app\models\CommonUser;

class UserController extends DefaultController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator']['except'] = ['options'];    
        
        return $behaviors;
    }
    
    protected function verbs()
    {
        return [
            'index'=>['GET', 'POST'],
            'create'=>['POST'],
            'update'=>['POST'],
            'view'=>['POST']
        ];
    }
    
    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider([
            'allModels'=>CommonUser::getUsers()
        ]);
        
        return $dataProvider->getModels();
    }
    
    public function actionCreate($notify = false)
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        
        if (($model = $this->findModel($params)) !== null) {
            return $model;
        }
        
        return CommonUser::createUser($params, $notify);
    }
    
    public function actionUpdate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        
        if (($model = $this->findModel($params)) !== null) {
            return CommonUser::updateUser($model, $params);
        }
        
        return null;
    }
    
    public function actionView()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        
        return $this->findModel($params);
    }
    
    protected function findModel($params)
    {
        return CommonUser::getUser($params);
    }        
}