<?php
namespace app\models\user\user_params;

use Yii;
use yii\db\ActiveRecord;
use app\models\user\user_params\Statistic;

class UserTemperature extends ActiveRecord
{
    public $delete;
    
    public static function tableName()
    {
        return 'user_p_temperature';
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
            [['user_id', 'temperature', 'created_at'], 'required'],
            [['temperature'], 'double'],
            [['user_id'], 'string', 'max'=>255],
            [['created_at', 'delete'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'temperature'=>'Температура, °C',
            'created_at'=>'Дата',
            'delete'=>'Удалить запись'
        ];
    }
    
    public static function getStatistic($param_name, $is_detail = false)
    {
        return Statistic::getStatistic(self::find(), ['temperature'], $param_name, null, $is_detail);
    }
}