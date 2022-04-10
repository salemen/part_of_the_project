<?php
// Заявка на расшифровку результатов ЭКГ (медсервисы)

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\forms\CardioForm;
use app\models\cardio\Cardio;
use app\models\payments\Payments;

class CardioController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index'],
                        'allow'=>true,
                        'roles'=>['?']
                    ],
                    [
                        'allow'=>true,                        
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $model = new CardioForm();
        
        if ($model->load(Yii::$app->request->post()) && $cardio = $model->save()) {
            $params = [
                'customerNumber'=>$cardio->patient_id,   
                'serviceType'=>Payments::TYPE_CARDIOGRAM,
                'serviceNumber'=>$cardio->id,                          
                'sum'=>Yii::$app->params['price']['cardio'] . '.00'                   
            ];

            return Yii::$app->yakassa->payment($params);
        }
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }  
    
    public function actionCardioPay()
    {
        $cardio_id = Yii::$app->request->post('cardio_id');
        
        if (Yii::$app->request->isAjax && $cardio_id) {
            $cardio = Cardio::findOne($cardio_id);
            if ($cardio) {                
                $params = [
                    'customerNumber'=>$cardio->patient_id,   
                    'serviceType'=>Payments::TYPE_CARDIOGRAM,
                    'serviceNumber'=>$cardio->id,                          
                    'sum'=>Yii::$app->params['price']['cardio'] . '.00'                   
                ];

                return Yii::$app->yakassa->payment($params);
            }
        }
    }        
}