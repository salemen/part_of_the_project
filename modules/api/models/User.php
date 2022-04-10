<?php
namespace app\modules\api\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{    
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    
    public static function tableName()
    {
        return 'api_user';
    }

    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash'], 'required'],
            [['status'], 'integer'],
            [['username', 'password_hash', 'description'], 'string', 'max'=>255],
            [['auth_key'], 'string', 'max'=>32],
            [['username'], 'unique']
        ];
    }
    
    public static function findIdentity($id)
    {
        return static::findOne(['id'=>$id, 'status'=>self::STATUS_ACTIVE]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key'=>$token, 'status'=>self::STATUS_ACTIVE]);
    }
    
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }        
}