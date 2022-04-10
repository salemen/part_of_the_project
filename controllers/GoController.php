<?php
// Быстрый переход по краткой ссылке

namespace app\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\Controller;
use app\forms\GoForm;
use app\models\other\ShortLinks;

class GoController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['get-link'],
                'rules'=>[
                    [
                        'allow'=>true,       
                        'actions'=>['get-link'],
                        'roles'=>['@']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'get-link'=>['post']
                ]
            ]
        ];
    }
    
    public function actionTo($id)
    {
        try {
            $model = new GoForm($id);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        return $model->login() ? $this->redirect([$model->url]) : $this->goHome();
    }   
    
    public function actionGetLink()
    {
        $data = Yii::$app->request->post();
        
        if ($data) {
            $url = $data['url'];
            $user_id = $data['user_id'];        
        
            $model = ShortLinks::findOne(['url'=>$url, 'user_id'=>$user_id]);
            
            if (!$model) {
                $model = new ShortLinks(['url'=>$url, 'user_id'=>$user_id]);
                $model->generateHash();
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Возникла внутренняя ошибка сервера.');
                }
            }
            
            return $this->renderAjax('/site/go', [
                'model'=>$model
            ]);
        }
        
        throw new BadRequestHttpException('Параметры переданы неверно.');
    }        
}