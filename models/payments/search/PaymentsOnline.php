<?php
namespace app\models\payments\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\payments\PaymentsOnline as PaymentsOnlineModel;

class PaymentsOnline extends PaymentsOnlineModel
{
    public function rules()
    {
        return [
            [['created_at'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentsOnlineModel::find();

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
            'is_test'=>$this->is_test,
            'status'=>$this->status,
            'created_at'=>$this->created_at
        ]);

        $query->andFilterWhere(['like', 'invoice_id', $this->invoice_id])
            ->andFilterWhere(['like', 'service_id', $this->service_id])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'pay_amount', $this->pay_amount])
            ->andFilterWhere(['like', 'pay_result', $this->pay_result])
            ->andFilterWhere(['like', 'pay_paycash', $this->pay_paycash])
            ->andFilterWhere(['like', 'pay_type', $this->pay_type]);

        return $dataProvider;
    }
    
    public function searchStat($params)
    {
        $query = PaymentsOnlineModel::find();

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->created_at) {
            $range = $this->created_at;
            switch ($range) {
                case 'currentMonth':
                    $start = date('01.m.Y');
                    $end = date('t.m.Y');                    
                    break;
                case 'prevMonth':
                    $start = date('01.m.Y', strtotime(date('Y-m') . " -1 month"));
                    $end = date('t.m.Y', strtotime(date('Y-m') . " -1 month"));
                    break;
                default:
                    $start = implode('.', ['01', '01', $range]);
                    $end = implode('.', ['31', '12', $range]);
                    break;
            }
            
            $query->andFilterWhere(['between', 'created_at', strtotime($start), strtotime($end)]);
        }

        return $dataProvider;
    }
}