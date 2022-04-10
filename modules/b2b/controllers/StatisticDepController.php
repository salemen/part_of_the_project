<?php
namespace app\modules\b2b\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\consult\Consult;
use app\models\data\Department;
use app\models\employee\EmployeePosition;

class StatisticDepController extends Controller
{       
    public function actionIndex($org = null, $period = null)
    {          
        $data = $this->getStatistic($org, $period);
        $title = 'Статистика проведенных консультаций по подразделениям (количество и выручка)';              
                        
        return $this->render('index', [
            'data'=>$data,
            'org'=>$org,
            'title'=>$title,
            'period'=>$period
        ]);
    }
    
    public function actionView()
    {        
        $dep_name = Yii::$app->request->post('dep_name');
        $period = Yii::$app->request->post('period');
        
        $dep = Department::findOne(['name'=>$dep_name]);
        
        $query = Consult::find()
            ->joinWith(['department', 'payment'])
            ->where([
                'dep_id'=>$dep->id,
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ]);
        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'consult.created_at', $start_date, $end_date]);
        }
                
        $query->orderBy(['SUM(shopSumAmount)'=>SORT_DESC])->groupBy(['dep_id', 'employee_id']);
        
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false,
            'sort'=>false
        ]);
        
        return $this->renderAjax('view', [
            'dataProvider'=>$dataProvider,
            'period'=>$period
        ]); 
    }       
    
    protected function getStatistic($org, $period)
    {
        $result = [];
            
        $query = Consult::find()
            ->select('dep_id')
            ->joinWith('payment')
            ->where([
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ]);
        
        $depIds = [];
        $deps = ($org) ? Department::findAll(['org_id'=>$org]) : Department::find()->where(['IN', 'org_id', EmployeePosition::getOrgIds()])->all();
        if ($deps) {
            foreach ($deps as $dep) {
                array_push($depIds, $dep->id);
            }                
        }
        
        $query->andWhere(['IN', 'dep_id', $depIds]);
        
        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }    
            
            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }
        
        $query->distinct();
        
        $model = $query->all();
        
        if ($model) {
            foreach ($model as $key=>$value) {
                $backgroundColor = AppHelper::generateHex($key);
                $dep_id = $value->dep_id;
                $dep_name = $value->department->name;
                
                $result['labels'][$key] = $dep_name;
                $result['datasets'][0]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][0]['data'][$key] = Consult::getConsultCountByParams(null, $dep_id, $period); 
                $result['datasets'][0]['label'][$key] = $dep_name;
                $result['datasets'][1]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][1]['data'][$key] = Consult::getConsultSumByParams(null, $dep_id, $period);
                $result['datasets'][1]['label'][$key] = $dep_name;
            }
        }            
                
        return $result;
    }
}