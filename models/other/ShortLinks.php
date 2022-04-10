<?php
namespace app\models\other;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ShortLinks extends ActiveRecord
{
    public static function tableName()
    {
        return 'short_links';
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'url', 'hash'], 'required'],
            [['created_at'], 'integer'],
            [['user_id', 'url'], 'string', 'max'=>255],
            [['hash'], 'string', 'max'=>32],
            [['hash'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'url'=>'Ссылка',
            'hash'=>'Hash',
            'created_at'=>'Дата'
        ];
    }
    
    public function generateHash()
    {
        $this->hash = Yii::$app->security->generateRandomString(8);
    }        
}