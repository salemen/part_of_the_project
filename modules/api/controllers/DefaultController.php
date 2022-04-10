<?php
namespace app\modules\api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\Response;

class DefaultController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actions()
    {        
        return [
            'options'=>[
                'class'=>OptionsAction::className()
            ]
        ];
    }
    
    public function beforeAction($action)
    {
        Yii::$app->request->parsers = [ 'application/json'=>'yii\web\JsonParser' ];
        Yii::$app->response->charset = 'UTF-8';
        
        return parent::beforeAction($action);
    }
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator'] = [
            'class'=>HttpBearerAuth::className()
        ];
        $behaviors['contentNegotiator'] = [
            'class'=>ContentNegotiator::className(),
            'formats'=>[
                'application/json'=>Response::FORMAT_JSON
            ]
        ];
        $behaviors['corsFilter'] = [
            'class'=>Cors::className(),
            'cors'=>[
                'Access-Control-Allow-Credentials'=>true,
                'Access-Control-Expose-Headers'=>[
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
                'Access-Control-Max-Age'=>3600,
                'Access-Control-Request-Headers'=>['*'],
                'Access-Control-Request-Method'=>['GET', 'POST', 'OPTIONS'],
                'Origin'=>Yii::$app->params['allowedOrigin']
            ]
        ];        
        
        return $behaviors;
    }
}