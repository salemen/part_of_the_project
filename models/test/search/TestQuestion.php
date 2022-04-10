<?php
namespace app\models\test\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\test\TestQuestion as TestQuestionModel;

class TestQuestion extends TestQuestionModel
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
        $query = TestQuestionModel::find()->where(['group_id'=>$group_id]);       

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
        
        return $dataProvider;
    }
}

