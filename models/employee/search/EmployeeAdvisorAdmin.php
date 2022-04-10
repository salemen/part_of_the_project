<?php
namespace app\models\employee\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\employee\EmployeeAdvisor;

class EmployeeAdvisorAdmin extends EmployeeAdvisor
{         
    public $city;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['employee_id', 'city'], 'save']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {        
        $query = EmployeeAdvisor::find()->joinWith('employee');
        
        $this->load($params);  

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);
        
        $query->andFilterWhere([
            'employee.id'=>$this->employee_id,
            'employee.city'=>$this->city,
            'employee_advisor.status'=>$this->status
        ]);                

        return $dataProvider;
    }  
    
    public function searchCorp($params)
    {        
        $query = EmployeeAdvisor::find()->joinWith(['employee', 'employee.position']);
        
        $this->load($params);  

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);
        
        $query->andFilterWhere([
            'employee.id'=>$this->employee_id
        ]);                

        return $dataProvider;
    }
}