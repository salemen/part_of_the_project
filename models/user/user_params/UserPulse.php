<?php
namespace app\models\user\user_params;

use Yii;
use yii\db\ActiveRecord;
use app\models\user\user_params\Statistic;

class UserPulse extends ActiveRecord
{
    const CONDITION_CALM = 0;
    const CONDITION_ACTIVE = 10;
    
    public $delete;

    public static function tableName()
    {
        return 'user_p_pulse';
    }
    
    public function beforeSave($insert) 
    {
        $this->created_at = strtotime($this->created_at);
        
        $model = self::findOne(['user_id'=>Yii::$app->user->id, 'condition'=>$this->condition, 'created_at'=>$this->created_at]);

        if ($model && $this->isNewRecord) {
            $model->delete();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function rules()
    {
        return [
            [['user_id', 'pulse', 'created_at'], 'required'],
            [['condition'], 'required', 'message'=>'Выберите один из параметров'],
            [['pulse'], 'double'],
            [['condition', 'delete'], 'integer'],
            [['user_id'], 'string', 'max'=>255],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'pulse'=>'Пульс, уд/мин',
            'condition'=>'Услвовие',
            'created_at'=>'Дата',
            'delete'=>'Удалить запись'
        ];
    }
    
    public static function getStatistic($param_name, $condition = null, $is_detail = false)
    {
        return Statistic::getStatistic(self::find(), ['pulse'], $param_name, $condition, $is_detail);
    }
    
    public static function getConditions()
    {
        return [
            self::CONDITION_CALM=>'Покой',
            self::CONDITION_ACTIVE=>'Активность'
        ];
    }
}