<?php

namespace app\modules\b2b\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\b2b\models\Cardio_one;


class Cardio_oneSearch extends Cardio_one
{

    public function rules()
    {
        return [
            [['id', 'is_end', 'is_payd', 'created_at'], 'integer'],
            [['employee_id', 'patient_id', 'ekg_date', 'patient_height', 'patient_weight', 'patient_sicks', 'patient_drugs', 'patient_target'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Cardio_one::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_end' => $this->is_end,
            'is_payd' => $this->is_payd,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'patient_id', $this->patient_id])
            ->andFilterWhere(['like', 'ekg_date', $this->ekg_date])
            ->andFilterWhere(['like', 'patient_height', $this->patient_height])
            ->andFilterWhere(['like', 'patient_weight', $this->patient_weight])
            ->andFilterWhere(['like', 'patient_sicks', $this->patient_sicks])
            ->andFilterWhere(['like', 'patient_drugs', $this->patient_drugs])
            ->andFilterWhere(['like', 'patient_target', $this->patient_target]);

        return $dataProvider;
    }
}
