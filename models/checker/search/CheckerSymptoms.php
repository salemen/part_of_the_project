<?php
namespace app\models\checker\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\checker\CheckerSymptoms as CheckerSymptomsModel;

class CheckerSymptoms extends CheckerSymptomsModel
{
    public $lit;
    public $search;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['lit', 'search'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CheckerSymptomsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'name', $this->lit . '%', false]);
        $query->andFilterWhere(['like', 'name', $this->search]);

        return $dataProvider;
    }
}
