<?php
namespace app\models\vaccine\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\vaccine\VacAge as VacAgeModel;

class VacAge extends VacAgeModel
{        
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = VacAgeModel::find()->joinWith(['relations']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false,
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