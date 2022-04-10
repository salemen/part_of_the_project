<?php
namespace app\models\cron;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CronNotification extends ActiveRecord
{
    const TYPE_MESSAGE = 0;
    const TYPE_NEW_CONSULT = 10;
    const TYPE_NEW_CARDIO = 20;
    const TYPE_NEW_USER = 30;
    const TYPE_NEW_MESSAGE_TO_DOC = 40;
    const TYPE_NEW_MESSAGE_TO_PATIENT = 50;
    const TYPE_CARDIO_SUCCESS = 60;
    const TYPE_NEW_EMPLOYEE = 70;
    
    const STATUS_NOT_SEND = 0;
    const STATUS_SEND = 1;
    
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
            [['target', 'type'], 'required'],
            [['message'], 'string'],
            [['type', 'is_sent', 'created_at', 'updated_at'], 'integer'],
            [['target'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'target'=>'Target',
            'message'=>'Message',
            'type'=>'Type',
            'is_sent'=>'Is Sent',
            'created_at'=>'Created At',
            'updated_at'=>'Updated At'
        ];
    }
}