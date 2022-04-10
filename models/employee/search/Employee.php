<?php
namespace app\models\employee\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\employee\Employee as EmployeeModel;

class Employee extends EmployeeModel
{
    public $search;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['search'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EmployeeModel::find()->joinWith(['roles']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['OR',
            ['like', 'fullname', $this->search],
            ['like', 'phone', $this->search],
            ['like', 'phone_work', $this->search],
            ['like', 'email', $this->search]
        ]);

        return $dataProvider;
    }
}