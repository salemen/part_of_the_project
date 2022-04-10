<?php
namespace app\models\test\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\test\TestUserSession as TestUserSessionModel;

class TestUserSession extends TestUserSessionModel
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
        $query = TestUserSessionModel::find()->joinWith(['employee', 'patient', 'test']);       

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['OR',
            ['like', 'test.name', $this->search],
            ['like', 'patient.fullname', $this->search],
            ['like', 'employee.fullname', $this->search]
        ]);
        
        return $dataProvider;
    }
}

