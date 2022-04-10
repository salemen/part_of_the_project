<?php

namespace app\modules\b2b\models;

use app\modules\b2b\models\Consult_one;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\modules\b2b\models\Cardio_one;
use yii\helpers\ArrayHelper;
use Yii;


class Consult_oneSearch extends Consult_one
{

    public function rules()
    {
        return [
            [['id', 'dep_id', 'e_hide', 'p_hide', 'is_canceled', 'is_end', 'is_payd', 'is_special', 'created_at', 'ended_at'], 'integer'],
            [['employee_id', 'patient_id', 'comment'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        if(!empty($_GET['Employee']['fullname'])) {
            $fullname = $_GET['Employee']['fullname'];
        }

        if(!empty($_GET['uder'])) {
            $uder = $_GET['uder'];

        }
        if (empty($uder)){
            $query = Consult_one::find()
                ->where(['is_end' => '1'])
                ->andWhere(['is_payd' => '1'])
                ->joinWith(['employee', 'patient', 'payment']);
        }elseif (!empty($uder)){
            $query = Consult_one::find()->where(['is_canceled' => 1])->joinWith(['employee', 'patient', 'payment']);

        }


        $dataProvider = new ActiveDataProvider([
            'query'=> $query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!is_null($this->created_at) && strpos($this->created_at, '-') !== false) {
            list($start_cr, $end_cr) = explode('-', $this->created_at);
            if ($start_cr == $end_cr) { $end_cr += 86400; }

            $query->andWhere(['between', 'consult.created_at', strtotime($start_cr), strtotime($end_cr)]);
        }

        if (!is_null($this->ended_at) && strpos($this->ended_at, '-') !== false) {
            list($start_e, $end_e) = explode('-', $this->ended_at);
            if ($start_e == $end_e) { $end_e += 86400; }

            $query->andWhere(['between', 'consult.ended_at', strtotime($start_e), strtotime($end_e)]);
        }

        // здесь выводиться и считается поля поиска для таблички GreadView

        $query->andFilterWhere([
            'employee.id'=>$this->employee_id,
            'patient.id'=>$this->patient_id,
            'is_special'=>$this->is_special
        ]);

        return $dataProvider;
    }
}
