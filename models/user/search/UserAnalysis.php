<?php
namespace app\models\user\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\user\UserAnalysis as UserAnalysisModel;

class UserAnalysis extends UserAnalysisModel
{
    public function rules()
    {
        return [
            [['id', 'type_id', 'index_id', 'unit_id', 'is_lab', 'lab_id', 'lab_number', 'created_at'], 'integer'],
            [['value', 'user_id'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserAnalysisModel::find()->joinWith(['researchIndex', 'researchType', 'researchUnit']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>30
            ],
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'=>$this->id,
            'user_analysis.type_id'=>$this->type_id,
            'index_id'=>$this->index_id,
            'unit_id'=>$this->unit_id,
            'is_lab'=>$this->is_lab,
            'lab_id'=>$this->lab_id,
            'lab_number'=>$this->lab_number,
            'created_at'=>$this->created_at
        ]);

        $query->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}