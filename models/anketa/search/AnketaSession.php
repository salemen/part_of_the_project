<?php
namespace app\models\anketa\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\AnketaSession as AnketaSessionModel;

class AnketaSession extends AnketaSessionModel
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
        $query = AnketaSessionModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        return $dataProvider;
    }
    
    public function searchList($params)
    {
        $query = AnketaSessionModel::find()->joinWith(['employee', 'patient']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['OR',
            ['like', 'patient.fullname', $this->search],
            ['like', 'patient.phone', $this->search],
            ['like', 'patient.email', $this->search],
            ['like', 'employee.fullname', $this->search],            
            ['like', 'employee.phone', $this->search],
            ['like', 'employee.phone_work', $this->search],
            ['like', 'employee.email', $this->search]
        ]);
        
        $query->orderBy(['created_at'=>SORT_DESC]);

        return $dataProvider;
    }
}