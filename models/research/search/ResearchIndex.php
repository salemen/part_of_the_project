<?php
namespace app\models\research\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\research\ResearchIndex as ResearchIndexModel;

class ResearchIndex extends ResearchIndexModel
{
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['id', 'method_id', 'method_alt_id', 'type_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_alt', 'interp_down', 'interp_up', 'comment'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ResearchIndexModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'name'=>$this->name
        ]);

        return $dataProvider;
    }
}