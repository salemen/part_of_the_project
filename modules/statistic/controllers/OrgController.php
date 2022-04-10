<?php
namespace app\modules\statistic\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\consult\Consult;
use app\models\data\Department;
use app\models\data\Organization;

class OrgController extends Controller
{       
    public function actionIndex($org = null, $period = null)
    {          
        $data = $this->getStatistic($org, $period);
        $title = 'Статистика проведенных консультаций по организациям (количество и выручка)';
                
        return $this->render('index', [            
            'data'=>$data,
            'org'=>$org,
            'title'=>$title,
            'period'=>$period
        ]);
    }
    
    public function actionView()
    {        
        $depIds = [];
        $org_name = Yii::$app->request->post('org_name');
        $period = Yii::$app->request->post('period');
        $deps = Department::find()
            ->joinWith('organization')
            ->where(['data_org.name'=>$org_name, 'data_org.is_santal'=>1, 'data_dep.is_santal'=>1])
            ->all();
        
        if ($deps) {
            foreach ($deps as $dep) {
                array_push($depIds, $dep->id);
            }
        }       
        
        $query = Consult::find()
            ->joinWith('payment')
            ->where([
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ])
            ->andWhere(['IN', 'dep_id', $depIds]);
        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }
        
        $query->orderBy(['SUM(shopSumAmount)'=>SORT_DESC])->groupBy(['dep_id', 'employee_id']);
                
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false,
            'sort'=>false
        ]);
        
        return $this->renderAjax('view', [
            'dataProvider'=>$dataProvider,
            'org_name'=>$org_name,
            'period'=>$period
        ]);  
    } 
    
    protected function getStatistic($org, $period)
    {
        $result = [];
        
        $query = Organization::find()
            ->select('data_org.id, data_org.name')
            ->leftJoin('data_dep', 'data_dep.org_id = data_org.id')    
            ->leftJoin('consult', 'consult.dep_id = data_dep.id')
            ->leftJoin('payments', 'payments.orderNumber = consult.id')
            ->where(['data_org.is_santal'=>1, 'data_dep.is_santal'=>1, 'is_hidden'=>0, 'data_org.status'=>10]);
        
        if ($org) {
            $query->andWhere(['data_org.id'=>$org]);
        }
        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'consult.created_at', $start_date, $end_date]);
        }
        
        $query->distinct('data_org.org_name')->all();
        
        $model = $query->all();
        
        if ($model) {
            foreach ($model as $key=>$value) {
                $backgroundColor = AppHelper::generateHex($key);
                $org_id = $value->id;
                $org_name = $value->name;
                
                if ($org && $this->getConsultCount($org_id, $period) == 0) {
                    continue;
                }
                
                $result['labels'][$key] = $org_name;
                $result['datasets'][0]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][0]['data'][$key] = $this->getConsultCount($org_id, $period); 
                $result['datasets'][0]['label'][$key] = $org_name;
                $result['datasets'][1]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][1]['data'][$key] = $this->getConsultSum($org_id, $period);
                $result['datasets'][1]['label'][$key] = $org_name;
            }            
        }        
                
        return $result;
    }
    
    protected function getConsultCount($org_id, $period)
    {        
        $result = 0;        
        $model = Department::findAll(['org_id'=>$org_id, 'status'=>10]);
        
        if ($model) {
            foreach ($model as $value) {
                $result += Consult::getConsultCountByParams(null, $value->id, $period);
            }
        }
        
        return $result;
    }      
    
    protected function getConsultSum($org_id, $period)
    {        
        $result = 0;
        $model = Department::findAll(['org_id'=>$org_id, 'status'=>10]);
        
        if ($model) {
            foreach ($model as $value) {
                $result += Consult::getConsultSumByParams(null, $value->id, $period);
            }
        }
        
        return $result;
    } 
}