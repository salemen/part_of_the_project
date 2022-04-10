<?php
namespace app\models\test\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\test\TestGroup as TestGroupModel;

class TestGroup extends TestGroupModel
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

    public function search($params, $test_id)
    {
        $query = TestGroupModel::find()->where(['test_id'=>$test_id]);       

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

