<?php
namespace app\models\patient\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\patient\Patient as PatientModel;

class Patient extends PatientModel
{
    public $search;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['created_at', 'search'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PatientModel::find();

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
            ['like', 'email', $this->search]
        ]);

        return $dataProvider;
    }
    
    public function searchStat($params)
    {
        $query = PatientModel::find();

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
            
            $query->andFilterWhere(['between', 'created_at', strtotime($start), strtotime($end)]);
        }

        return $dataProvider;
    }
}