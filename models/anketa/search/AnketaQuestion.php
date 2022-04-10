<?php
namespace app\models\anketa\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\AnketaQuestion as AnketaQuestionModel;

class AnketaQuestion extends AnketaQuestionModel
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

    public function search($params, $anketa_id)
    {
        $query = AnketaQuestionModel::find()->where(['anketa_id'=>$anketa_id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['OR',
            ['like', 'name', $this->search]
        ]);
        
        $query->orderBy('position');

        return $dataProvider;
    }
}