<?php
namespace app\models\payments\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\payments\Payments as PaymentsModel;

class Payments extends PaymentsModel
{
    public $city;
    public $date;    
    public $employee;    
    public $patient;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['city', 'date', 'employee', 'patient'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentsModel::find()->joinWith('employee');

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if (!is_null($this->date) && strpos($this->date, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->date);
            if ($start_date == $end_date) {
                $query->andFilterWhere(['between', 'orderCreatedDatetime', strtotime($start_date), strtotime($end_date) + 86400]);
            } else {
                $query->andFilterWhere(['between', 'orderCreatedDatetime', strtotime($start_date), strtotime($end_date)]);
            }            
        }

        $query->andFilterWhere([
            'employee.city'=>$this->city,
            'employee.id'=>$this->employee,
            'customerNumber'=>$this->patient
        ]);


        return $dataProvider;
    }
}