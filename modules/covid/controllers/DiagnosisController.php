<?php
namespace app\modules\covid\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class DiagnosisController extends Controller
{    
    public function actionIndex()
    {   
        $model = new DynamicModel(['number']);
        $model->addRule(['number'], 'required')
            ->addRule(['number'], 'integer')
            ->setAttributeLabels([
                'number'=>'Код исследования'
            ]);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {            
            if ($model->validate()) {
                $pat_code = $model->number;            
            
                $command = Yii::$app->db_0370->createCommand("SELECT * FROM covid_registry WHERE pat_code = '{$pat_code}' ORDER BY id");
                $values = $command->queryOne();
                if (Yii::$app->params['logPathCovid'] !== '/' && $values) {
                    $this->saveLog($values);
                }

                return $this->renderAjax('result', [
                    'number'=>$model->number,
                    'values'=>$values
                ]);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }
        } 
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }
    
    protected function saveLog($data)
    {
        $domain = Url::base('https');
        $file = Yii::$app->params['logPathCovid'];
        $log_date = date('d.m.Y / H:i:s');
        $file_data = "{$log_date} - {$data['pat_code']} - {$data['test_result']} - {$domain}\n";
        $file_data .= file_get_contents($file);
        
        file_put_contents($file, $file_data);
    }        
}