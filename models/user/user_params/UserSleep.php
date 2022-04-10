<?php
namespace app\models\user\user_params;

use Yii;
use yii\db\ActiveRecord;
use app\models\user\user_params\Statistic;

class UserSleep extends ActiveRecord
{      
    public $delete;
    
    public static function tableName()
    {
        return 'user_p_sleep';
    }
    
    public function beforeSave($insert) 
    {
        $this->created_at = strtotime($this->created_at);
        
        $model = self::findOne(['user_id'=>Yii::$app->user->id, 'created_at'=>$this->created_at]);

        if ($model && $this->isNewRecord) {
            $model->delete();
        }
        
        return parent::beforeSave($insert);
    }
    
    public function rules()
    {
        return [
            [['user_id', 'sleep', 'created_at'], 'required'],
            [['sleep'], 'double'],
            [['user_id'], 'string', 'max'=>255],
            [['created_at', 'delete'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'sleep'=>'Сон, ч',
            'created_at'=>'Дата',
            'delete'=>'Удалить запись'
        ];
    }
    
    public static function getStatistic($param_name, $is_detail = false)
    {
        return Statistic::getStatistic(self::find(), ['sleep'], $param_name, null, $is_detail);
    }
}