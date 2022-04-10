<?php
namespace app\models\test\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\test\Test as TestModel;

class Test extends TestModel
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

    public function search($params)
    {
        $query = TestModel::find();       

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['OR',
            ['like', 'name', $this->search],
            ['like', 'desc', $this->search]
        ]);
        
        return $dataProvider;
    }
}