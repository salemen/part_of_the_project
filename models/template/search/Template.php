<?php
namespace app\models\template\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\template\Template as TemplateModel;

class Template extends TemplateModel
{
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['id', 'type_id', 'created_at'], 'integer'],
            [['employee_id', 'patient_id'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TemplateModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'=>$this->id,
            'type_id'=>$this->type_id,
            'created_at'=>$this->created_at
        ]);

        $query->andFilterWhere(['like', 'employee_id', $this->employee_id])
            ->andFilterWhere(['like', 'patient_id', $this->patient_id]);

        return $dataProvider;
    }
}