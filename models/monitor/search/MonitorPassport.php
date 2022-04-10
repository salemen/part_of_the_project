<?php
namespace app\models\monitor\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\monitor\MonitorPassport as MonitorPassportModel;

class MonitorPassport extends MonitorPassportModel
{
    public $search;

    public function formName() { return ''; }

    public function rules()
    {
        return [
            [['protocol_type'], 'integer'],
            [['reason', 'search'], 'safe'],
            [['clinic'], 'string', 'max'=>255]

        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MonitorPassportModel::find()->joinWith(['employee', 'patient', 'data']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            // 'protocol_type'=>$this->protocol_type,
            'reason'=>$this->reason,

        ]);

        $query->andFilterWhere(['OR',
            ['like', 'employee.fullname', $this->search],
            ['like', 'employee.phone', $this->search],
            ['like', 'employee.phone_work', $this->search],
            ['like', 'employee.city', $this->search],
            ['like', 'patient.fullname', $this->user_id],
            ['like', 'patient.phone', $this->search],
            ['like', 'patient.city', $this->search],
            ['like', 'clinic', $this->clinic],

        ]);

        return $dataProvider;
    }
}