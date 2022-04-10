<?php
// Раздел "Просмотр результатов анализов"
// TODO Перенести в раздел "интерпретация результатов анализов"

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\web\Controller;
use app\models\other\Slider;

class ResearchController extends Controller
{
    public function beforeAction($action)
    {            
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $model = new DynamicModel(['number', 'year']);
        $model->addRule(['number', 'year'], 'required')
            ->addRule(['number', 'year'], 'integer')
            ->setAttributeLabels([
                'number'=>'Код исследования',
                'year'=>'Год исследования'
            ]);
        
        return $this->render('index', [
            'model'=>$model,
            'slider'=>Slider::find()->where(['show_research'=>true])->orderBy('position')->all()
        ]);      
    }
    
    public function actionResult()
    {
        $model = new DynamicModel(['number', 'year']);
        $model->addRule(['number', 'year'], 'integer');
        
        if ($model->load(Yii::$app->request->post())) {
            $number = $model->number;
            $from = '01.01.' . $model->year;
            $to = '31.12.' . $model->year;
            $city = 2;							
            $ws = strval($number);
            if (mb_strlen($ws) == 7) {
                $ws = intval($ws[0]);
                $city = ($ws == 2 || $ws == 1) ? 1 : 2;
            }
            
            return $this->render('result', [
                'number'=>strval($number),
                'from'=>$from,
                'to'=>$to,
                'city'=>$city
            ]);
        } 
        
        return $this->redirect(['index']);
    }     
}