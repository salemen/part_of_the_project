<?php
namespace app\models\anketa\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\AnketaRiskGroup as AnketaRiskGroupModel;

class AnketaRiskGroup extends AnketaRiskGroupModel
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

    public function search($params, $category_id)
    {
        $query = AnketaRiskGroupModel::find()->where(['category_id'=>$category_id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['OR',
            ['like', 'tactic', $this->search],
            ['like', 'risk_name', $this->search]
        ]);

        return $dataProvider;
    }
}

