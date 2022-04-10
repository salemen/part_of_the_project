<?php
namespace app\models\user\user_params;

use Yii;
use yii\db\ActiveRecord;
use app\models\user\user_params\Statistic;

class UserSugar extends ActiveRecord
{
    const CONDITION_MORNING = 0;
    const CONDITION_BEFORE_EAT = 10;
    const CONDITION_AFTER_EAT = 20;
    const CONDITION_EVENING = 30;
    
    public $delete;

    public static function tableName()
    {
        return 'user_p_sugar';
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
            [['user_id', 'sugar', 'created_at'], 'required'],
            [['condition'], 'required', 'message'=>'Выберите один из параметров'],
            [['sugar'], 'double'],
            [['condition'], 'integer'],
            [['user_id'], 'string', 'max'=>255],
            [['created_at', 'delete'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'sugar'=>'Сахар, ммоль/л',
            'condition'=>'Услвовие',
            'created_at'=>'Дата',
            'delete'=>'Удалить запись'
        ];
    }
    
    public static function getStatistic($param_name, $condition = null, $is_detail = false)
    {
        return Statistic::getStatistic(self::find(), ['sugar'], $param_name, $condition, $is_detail);
    }
    
    public static function getConditions()
    {
        return [
            self::CONDITION_MORNING=>'Утром',
            self::CONDITION_BEFORE_EAT=>'До еды',
            self::CONDITION_AFTER_EAT=>'После еды',
            self::CONDITION_EVENING=>'Вечером'
        ];
    }
}