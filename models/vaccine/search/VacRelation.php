<?php
namespace app\models\vaccine\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\vaccine\VacRelation as VacRelationModel;

class VacRelation extends VacRelationModel
{
    public $sickness;    
    public $vaccine;
    public $vac_dest;
    public $vac_org;
    public $vac_state;    
    public $vac_type;
    
    public function formName() { return ''; }
    
    public function rules()
    {
        return [
            [['sickness', 'vaccine', 'vac_dest', 'vac_org', 'vac_state', 'vac_type'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = VacRelationModel::find()->joinWith(['sickness', 'vaccine']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);
        
        $dataProvider->setSort([
            'attributes'=>[
                'sickness'=>[
                    'asc'=>['vac_sickness.name'=>SORT_ASC],
                    'desc'=>['vac_sickness.name'=>SORT_DESC]
                ],                
                'vaccine'=>[
                    'asc'=>['vac_vaccine.name'=>SORT_ASC],
                    'desc'=>['vac_vaccine.name'=>SORT_DESC]
                ],
                'vac_state'=>[
                    'asc'=>['vac_vaccine.state'=>SORT_ASC],
                    'desc'=>['vac_vaccine.state'=>SORT_DESC]
                ],
                'vac_type'=>[
                    'asc'=>['vac_vaccine.type'=>SORT_ASC],
                    'desc'=>['vac_vaccine.type'=>SORT_DESC]
                ]                               
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sick_id'=>$this->sickness,
            'vac_id'=>$this->vaccine,
            'vac_vaccine.state'=>$this->vac_state,
            'vac_vaccine.type'=>$this->vac_type
        ]);

        return $dataProvider;
    }
}