<?php
namespace app\models\anketa\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\AnketaPermission as AnketaPermissionModel;

class AnketaPermission extends AnketaPermissionModel
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

    public function search($params, $anketa_id)
    {
        $query = AnketaPermissionModel::find()->where(['anketa_id'=>$anketa_id]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}

