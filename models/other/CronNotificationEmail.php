<?php
namespace app\models\other;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CronNotificationEmail extends ActiveRecord
{
    public static function tableName()
    {
        return 'cron_notification';
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['target', 'subject', 'message'], 'required'],
            [['message'], 'string'],
            [['is_sent', 'created_at', 'updated_at'], 'integer'],
            [['target', 'subject'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'target'=>'Target',
            'subject'=>'Subject',
            'message'=>'Message',
            'is_sent'=>'Is Sent',
            'created_at'=>'Created At',
            'updated_at'=>'Updated At'
        ];
    }
}