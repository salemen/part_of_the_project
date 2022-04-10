<?php
namespace app\models\pz\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\pz\FluraJurnal as FluraJurnalModel;

class FluraJurnal extends FluraJurnalModel
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
        $query = FluraJurnalModel::find()->joinWith(['patient']);

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

        $query->andFilterWhere(['OR',
            ['like', 'patient.u_fam', $this->search],
            ['like', 'patient.u_ima', $this->search],
            ['like', 'patient.u_otc', $this->search],
            ['like', 'CONCAT(patient.u_fam, " ", patient.u_ima, " ", patient.u_otc)', $this->search]
        ]);

        return $dataProvider;
    }
}