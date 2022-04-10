<?php
namespace app\models\anketa\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\anketa\Anketa as AnketaModel;
use app\helpers\AppHelper;

class Anketa extends AnketaModel
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
        $query = AnketaModel::find();       

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
    
    public function searchByPermissions($params)
    {
        $anketa_ids = Anketa::find()->select('id')->indexBy('id')->asArray()->all();
        $user = Yii::$app->user->identity;
        $perms = AnketaPermission::find()->all();
        
        if ($perms) {
            foreach ($perms as $perm) {
                if (isset($anketa_ids[$perm->anketa_id]) && !$this->checkPermission($perm, $user)) {
                    unset($anketa_ids[$perm->anketa_id]);
                }
            }
        }

        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['IN', 'id', $anketa_ids]);
        
        return $dataProvider;
    }
    
    private function checkPermission($perm, $user)
    {
        $user_param = $this->getParam($perm, $user);
        $value = $perm->value;
        
        switch ($perm->operator) {
            case '>':
                return ($user_param > $value);
            case '<':
                return ($user_param < $value);
            case '!=':
                return ($user_param != $value);
            case '==':
                return ($user_param == $value);
        }
    }
    
    private function getParam($perm, $user)
    {
        switch ($perm->param_name) {
            case 'age':
                return AppHelper::calculateAge($user->user_birth);
            case 'sex':
                return $user->sex;
        }
    }
}