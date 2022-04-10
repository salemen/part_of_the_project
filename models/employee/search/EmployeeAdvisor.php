<?php
namespace app\models\employee\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\data\Department;
use app\models\employee\Employee;

class EmployeeAdvisor extends Employee
{
    public $empl_city;
    public $empl_dep;
    public $empl_name;
    public $empl_org;
    public $empl_pos;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['empl_city', 'empl_dep', 'empl_name', 'empl_org', 'empl_pos'], 'string']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Employee::find()->joinWith(['advisor',  'positionsActive']);

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $empl_dep = (isset($this->empl_dep) && $this->empl_dep !== '') ? Department::findOne($this->empl_dep)->alias : null;

        if ($this->empl_pos != 'Все специальности') {

            $query->andFilterWhere([
                'city' => $this->empl_city,
                'empl_dep' => $empl_dep,
                'org_id' => $this->empl_org,
                'empl_pos' => $this->empl_pos
            ]);
        } else {

            $query->andFilterWhere([
                'city' => $this->empl_city,
                'empl_dep' => $empl_dep,
                'org_id' => $this->empl_org
            ]);
        }

        $query->andFilterWhere(['like', 'fullname', $this->empl_name]);

        return $dataProvider;
    }

    public function searchcon($params)
    {
        $query = Employee::find()->joinWith(['advisors', 'positionsDoctorAll']);

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $empl_dep = (isset($this->empl_dep) && $this->empl_dep !== '') ? Department::findOne($this->empl_dep)->alias : null;

        if ($this->empl_pos != 'Все специальности') {

            $query->andFilterWhere([
                'city' => $this->empl_city,
                'empl_dep' => $empl_dep,
                'org_id' => $this->empl_org,
                'empl_pos' => $this->empl_pos
            ]);
        } else {

            $query->andFilterWhere([
                'city' => $this->empl_city,
                'empl_dep' => $empl_dep,
                'org_id' => $this->empl_org
            ]);
        }

        $query->andFilterWhere(['like', 'fullname', $this->empl_name]);

        return $dataProvider;
    }
}