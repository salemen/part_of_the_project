<?php
namespace app\models\consult\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\consult\Consult as ConsultModel;

class Consult extends ConsultModel
{    
    public $city;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['employee_id', 'patient_id', 'city', 'created_at', 'ended_at'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ConsultModel::find()->joinWith(['employee', 'patient', 'payment', 'department']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if (!is_null($this->created_at) && strpos($this->created_at, '-') !== false) {
            list($start_cr, $end_cr) = explode('-', $this->created_at);
            if ($start_cr == $end_cr) { $end_cr += 86400; }
            
            $query->andWhere(['between', 'consult.created_at', strtotime($start_cr), strtotime($end_cr)]);
        }
        
        if (!is_null($this->ended_at) && strpos($this->ended_at, '-') !== false) {
            list($start_e, $end_e) = explode('-', $this->ended_at);
            if ($start_e == $end_e) { $end_e += 86400; }
            
            $query->andWhere(['between', 'consult.ended_at', strtotime($start_e), strtotime($end_e)]);
        }

        $query->andFilterWhere([
            'employee.id'=>$this->employee_id,
            'employee.city'=>$this->city,            
            'patient.id'=>$this->patient_id
        ]);

        return $dataProvider;
    }
    
    public function searchStat($params)
    {
        $query = ConsultModel::find()->joinWith(['department', 'department.organization', 'employee', 'patient', 'payment']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if ($this->created_at) {
            $range = $this->created_at;
            switch ($range) {
                case 'currentMonth':
                    $start = date('01.m.Y');
                    $end = date('t.m.Y');                    
                    break;
                case 'prevMonth':
                    $start = date('01.m.Y', strtotime(date('Y-m') . " -1 month"));
                    $end = date('t.m.Y', strtotime(date('Y-m') . " -1 month"));
                    break;
                default:
                    $start = implode('.', ['01', '01', $range]);
                    $end = implode('.', ['31', '12', $range]);
                    break;
            }
            
            $query->andFilterWhere(['between', 'consult.created_at', strtotime($start), strtotime($end)]);
        }

        $query->andFilterWhere([
            'employee.id'=>$this->employee_id,
            'data_org.city'=>$this->city,
            'patient.id'=>$this->patient_id
        ]);

        return $dataProvider;
    }
}