<?php
namespace app\modules\user\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\models\user\user_params\UserCholesterol;
use app\models\user\user_params\UserHeight;
use app\models\user\user_params\UserPressure;
use app\models\user\user_params\UserPulse;
use app\models\user\user_params\UserSleep;
use app\models\user\user_params\UserSugar;
use app\models\user\user_params\UserTemperature;
use app\models\user\user_params\UserWeight;

class UserParamsController extends Controller
{    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['*'],
                'rules'=>[
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
        $cholesterol_stat = $this->getStatByParamName('cholesterol', UserCholesterol::CONDITION_COMMON);      
        $height_stat = $this->getStatByParamName('height');
        $pressure_stat = $this->getStatByParamName('pressure');
        $pulse_stat = $this->getStatByParamName('pulse', UserPulse::CONDITION_CALM);
        $sleep_stat = $this->getStatByParamName('sleep');
        $sugar_stat = $this->getStatByParamName('sugar', UserSugar::CONDITION_MORNING);  
        $temperature_stat = $this->getStatByParamName('temperature');
        $weight_stat = $this->getStatByParamName('weight');
                
        $cholesterol_conditions = UserCholesterol::getConditions();
        $pulse_conditions = UserPulse::getConditions();
        $sugar_conditions = UserSugar::getConditions();
        
        $params_stats = [
            'pressure'=>['name'=>'Артериальное давление', 'stat'=>$pressure_stat],
            'pulse'=>['name'=>'Пульс', 'stat'=>$pulse_stat, 'cond'=>['values'=>$pulse_conditions, 'checked_val'=>UserPulse::CONDITION_CALM]],
            'sleep'=>['name'=>'Сон', 'stat'=>$sleep_stat], 
            'temperature'=>['name'=>'Температура', 'stat'=>$temperature_stat],    
            'weight'=>['name'=>'Вес', 'stat'=>$weight_stat],                       
            'sugar'=>['name'=>'Сахар', 'stat'=>$sugar_stat, 'cond'=>['values'=>$sugar_conditions, 'checked_val'=>UserSugar::CONDITION_MORNING]], 
            'cholesterol'=>['name'=>'Холестерин', 'stat'=>$cholesterol_stat, 'cond'=>['values'=>$cholesterol_conditions, 'checked_val'=>UserCholesterol::CONDITION_COMMON]],
            'height'=>['name'=>'Рост', 'stat'=>$height_stat]             
        ];
        
        return $this->render('index', [
            'params_stats'=>$params_stats
        ]);
    }
    
    public function actionCrud()
    {
        $request = Yii::$app->request;
        
        if ($request->isAjax) {
            $param_name = $request->post('param_name');
            $model_id = $request->post('model_id');

            if (!$param_name) {
                throw new NotFoundHttpException('Параметр не найден, попробуйте позже');
            }
            
            $model = $this->getParamModel($param_name, $model_id);
            $model->user_id = Yii::$app->user->id;
            
            switch ($param_name) {
                case 'pressure':
                    $model->created_at = ($model_id) ? date("d.m.Y H:i", $model->created_at) : date("d.m.Y H:i");
                    break;
                default :
                    $model->created_at = ($model_id) ? date("d.m.Y", $model->created_at) : date("d.m.Y");
                    break;
            }
            
            if ($model->load($request->post())) {
                Yii::$app->response->format = 'json';
                
                if ($model->delete) {
                    $model->delete();
                    $stat = isset($model->condition) ? $model->getStatistic($param_name, $model->condition, false) : $model->getStatistic($param_name, false, false);
                    return ['success'=>true, 'stat'=>$stat, 'cond'=>isset($model->condition) ? $model->condition : null]; 
                }
                
                if ($model->save()) {
                    $stat = isset($model->condition) ? $model->getStatistic($param_name, $model->condition, false) : $model->getStatistic($param_name, false, false);
                    return ['success'=>true, 'stat'=>$stat, 'cond'=>isset($model->condition) ? $model->condition : null];    
                } else {
                    $validation = ActiveForm::validate($model);
                    return ['success'=>false, 'validation'=>$validation];
                }
            }

            return $this->renderAjax('_forms/form-' . $param_name, [
                'model'=>$model,
                'param_name'=>$param_name,
                'model_id'=>$model_id
            ]);
        }
    }
    
    public function actionView($param_name)
    {
        $stat = null;
        $conditions = null;
        
        switch ($param_name) {
            case 'cholesterol':
                $conditions = UserCholesterol::getConditions();
                $stat = $this->getStatByParamName($param_name, UserCholesterol::CONDITION_COMMON, true);
                break;
            case 'pulse':    
                $conditions = UserPulse::getConditions();
                $stat = $this->getStatByParamName($param_name, UserPulse::CONDITION_CALM, true);
                break;
            case 'sugar':
                $conditions = UserSugar::getConditions();
                $stat = $this->getStatByParamName($param_name, UserSugar::CONDITION_MORNING, true);
                break;
            default :
                $stat = $this->getStatByParamName($param_name, null, true);
                break;
        }
        
        return $this->render('view', [
            'param_name'=>$param_name,
            'stat'=>$stat,
            'conditions'=>$conditions
        ]);
    }
    
    public function actionGetStatByCondition()
    {
        $request = Yii::$app->request;
        
        if ($request->isAjax) {
            $param_name = $request->post('param_name');
            $condition = $request->post('condition');
            $is_detail = $request->post('is_detail');

            if ($param_name == null || $condition == null || $is_detail == null) {
                throw new NotFoundHttpException('Параметр не найден, попробуйте позже');
            }
            
            Yii::$app->response->format = 'json';
            return $this->getStatByParamName($param_name, $condition, $is_detail);
        }
    }
        
    protected function getStatByParamName($param_name, $condition = null, $is_detail = false)
    {
        switch ($param_name) {
            case 'cholesterol': return UserCholesterol::getStatistic($param_name, $condition, $is_detail);
            case 'height': return UserHeight::getStatistic($param_name, $is_detail);
            case 'pressure': return UserPressure::getStatistic($param_name, $is_detail);
            case 'pulse': return UserPulse::getStatistic($param_name, $condition, $is_detail);
            case 'sleep': return UserSleep::getStatistic($param_name, $is_detail);
            case 'sugar': return UserSugar::getStatistic($param_name, $condition, $is_detail);
            case 'temperature': return UserTemperature::getStatistic($param_name, $is_detail);
            case 'weight': return UserWeight::getStatistic($param_name, $is_detail);
            default : throw new NotFoundHttpException('Параметр не найден, попробуйте позже');
        }
    }

    protected function getParamModel($param_name, $model_id = null)
    {       
        switch ($param_name) {
            case 'cholesterol': return ($model_id) ? UserCholesterol::findOne($model_id) : new UserCholesterol();
            case 'height': return ($model_id) ? UserHeight::findOne($model_id) : new UserHeight();
            case 'pressure': return ($model_id) ? UserPressure::findOne($model_id) : new UserPressure();
            case 'pulse': return ($model_id) ? UserPulse::findOne($model_id) : new UserPulse();
            case 'sleep': return ($model_id) ? UserSleep::findOne($model_id) : new UserSleep();
            case 'sugar': return ($model_id) ? UserSugar::findOne($model_id) : new UserSugar();
            case 'temperature': return ($model_id) ? UserTemperature::findOne($model_id) : new UserTemperature();
            case 'weight': return ($model_id) ? UserWeight::findOne($model_id) : new UserWeight();
            default : throw new NotFoundHttpException('Параметр не найден, попробуйте позже');
        }
    }
}