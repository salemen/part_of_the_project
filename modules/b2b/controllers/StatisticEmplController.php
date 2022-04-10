<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\consult\Consult;
use app\models\data\Department;
use app\models\employee\EmployeePosition;
use app\models\payments\Payments;

class StatisticEmplController extends Controller
{       
    public function actionIndex($period = null)
    {          
        $data = $this->getStatistic($period);
        $title = 'Статистика проведенных консультаций по сотрудникам';
                
        return $this->render('index', [            
            'data'=>$data,
            'title'=>$title,
            'period'=>$period
        ]);
    }
    
    protected function getStatistic($period)
    {
        $result = [];
        
        $query = Consult::find()
            ->select('employee_id')
            ->joinWith(['department', 'payment'])
            ->where([
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ]);
        
        $depIds = [];
        $deps = Department::find()->where(['IN', 'org_id', EmployeePosition::getOrgIds()])->all();
        if ($deps) {
            foreach ($deps as $dep) {
                array_push($depIds, $dep->id);
            }                
        }
        
        $query->andWhere(['IN', 'dep_id', $depIds]);
                        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'consult.created_at', $start_date, $end_date]);
        }        
                
        $query->orderBy(['COUNT(employee_id)'=>SORT_DESC])->groupBy('employee_id');
        
        $model = $query->all();
        
        if ($model) {
            $key = 0;
            foreach ($model as $value) {
                $count = $this->getCousultCount($value->employee_id, $period);
                if ($count == 0) {
                    continue;                    
                }                      
                
                $result['labels'][$key] = $value->employee->fullname . ' (' . $count . ')';
                $result['datasets'][0]['backgroundColor'][$key] = AppHelper::generateHex($key);
                $result['datasets'][0]['data'][$key] = $count;                
                $result['datasets'][0]['label'][$key] = $value->employee->fullname;
                $key++;
            }
        }
        
        return $result;
    }
    
    protected function getCousultCount($employee_id, $period)
    {
        $query = Payments::find()
            ->joinWith('employeeConsult')
            ->where([
                'orderType'=>Payments::TYPE_CONSULT,
                'orderStatus'=>Payments::STATUS_PAYD,
                'isTest'=>false,
                'employee.id'=>$employee_id
            ]);
        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'orderCreatedDatetime', $start_date, $end_date]);
        }
        
        $result = $query->count();
        
        return (int)$result;
    }        
}