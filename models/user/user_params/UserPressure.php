<?php
namespace app\models\user\user_params;

use Yii;
use yii\db\ActiveRecord;
use app\models\user\user_params\Statistic;

class UserPressure extends ActiveRecord
{
    public $delete;
    
    public static function tableName()
    {
        return 'user_p_pressure';
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
            [['user_id', 'systole', 'diastole', 'created_at'], 'required'],
            [['systole', 'diastole'], 'integer'],
            [['user_id'], 'string', 'max'=>255],
            [['created_at', 'delete'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'systole'=>'Систола (верхнее)',
            'diastole'=>'Диастола (нижнее)',
            'created_at'=>'Дата',
            'delete'=>'Удалить запись'
        ];
    }
    
    public static function getStatistic($param_name, $is_detail = false)
    {
        return Statistic::getStatistic(self::find(), ['systole', 'diastole'], $param_name, null, $is_detail, 8, "d.m.Y H:i");
    }
}