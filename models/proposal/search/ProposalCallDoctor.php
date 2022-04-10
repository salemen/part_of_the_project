<?php
namespace app\models\proposal\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\proposal\ProposalCallDoctor as ProposalCallDoctorSearch;

class ProposalCallDoctor extends ProposalCallDoctorSearch
{
    public $city;
    public $created_at;

    public function formName() { return ''; }

    public function rules()
    {
        return [

            [['clinic','city','created_at'], 'string', 'max'=>255],

        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $date = Yii::$app->request->get('created_at');
        $dateup = Yii::$app->request->get('updated_at');

        if(isset($date)&&empty($dateup)){
            $unixDate = strtotime($date);
            $query = ProposalCallDoctorSearch::find()->where(['>', 'created_at', $unixDate])->orderBy(['created_at'=>SORT_DESC]);
        }elseif(empty($date)&&isset($dateup)){
            $unixDateup = strtotime($dateup);
            $query = ProposalCallDoctorSearch::find()->where(['<', 'updated_at', $unixDateup])->orderBy(['created_at'=>SORT_DESC]);
        }elseif(isset($date)&&isset($dateup)){
            $unixDate = strtotime($date);
            $unixDateup = strtotime($dateup);
            $query = ProposalCallDoctorSearch::find()->where(['between', 'created_at', $unixDate, $unixDateup])->orderBy(['created_at'=>SORT_DESC]);
        }else{
            $query = ProposalCallDoctorSearch::find()->orderBy(['created_at'=>SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query'=> $query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(['OR',
            ['like', 'city', $this->city],

        ]);

        return $dataProvider;
    }
}