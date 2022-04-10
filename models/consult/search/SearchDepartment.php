<?php

namespace app\models\consult\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\consult\ConsultDepartment;
use app\models\data\Department;


class SearchDepartment extends ConsultDepartment
{
    public function formName() { return ''; }

    public function rules()
    {
        return [
            [['id', 'dep_id', 'e_hide', 'p_hide', 'is_canceled', 'is_end', 'is_payd', 'is_special', 'created_at', 'ended_at'], 'integer'],
            [['employee_id', 'patient_id', 'comment'], 'safe'],
        ];
    }


    public function scenarios()
    {

        return Model::scenarios();
    }


    public function search($params)
    {
        $get = Yii::$app->request->get();
      
        if(isset($get['uder'])) {
            $query = ConsultDepartment::find()
                ->where(['is_canceled' => '1'])->joinWith(['employee', 'patient', 'payment', 'department']);
        }elseif(!isset($get['uder'])){
            $query = ConsultDepartment::find()
                ->where(['is_end' => '1'])
                ->andWhere(['is_payd' => '1'])
                ->andWhere(['is_canceled' => '0'])->joinWith(['employee', 'patient', 'payment', 'department']);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [

                ]
            ],

        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        if (!empty($get['Employee']['fullname'])) {
            $value = $get['Employee']['fullname'];
            $org = Department::find()->select('id')->andWhere(['org_id' => $value])->all();
        }else{
            $org = Department::find()->select('id')->all();
        }


        foreach ($org as  $item) {
            $value2[] = $item;
        }
        foreach ($value2 as $keys => $names) {
            foreach ($names as $key => $name) {
                $value3[$key][] = $name;
                continue;
            }
        }

        $dataProvider->query->where(['dep_id'=> $value3['id']]);

        $query->andFilterWhere([
            'employee.id'=>$this->employee_id,
            'patient.id'=>$this->patient_id,
            'is_special'=>$this->is_special
            
        ]);


        return $dataProvider;
    }
}
