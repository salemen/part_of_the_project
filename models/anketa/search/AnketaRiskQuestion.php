<?php
namespace app\models\anketa\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\AnketaRiskQuestion as AnketaRiskQuestionModel;

class AnketaRiskQuestion extends AnketaRiskQuestionModel
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

    public function search($params, $group_id)
    {
        $query = AnketaRiskQuestionModel::find()->where(['group_id'=>$group_id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['OR',
            ['like', 'anketa_question.name', $this->search],
        ]);

        return $dataProvider;
    }
}

