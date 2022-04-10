<?php
namespace app\models\vaccine\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\vaccine\VacAgeRelation as VacAgeRelationModel;

class VacAgeRelation extends VacAgeRelationModel
{  
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['age_id', 'sick_id'], 'integer']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = VacAgeRelationModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'age_id'=>$this->age_id,
            'sick_id'=>$this->sick_id
        ]);

        return $dataProvider;
    }
    
    public function searchByAge($params)
    {
        $query = VacAgeRelationModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false,
            'pagination'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['<=', 'age_id', $this->age_id]);
        
        $query->andFilterWhere([
            'sick_id'=>$this->sick_id
        ]);

        return $dataProvider;
    }
}