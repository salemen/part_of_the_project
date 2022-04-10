<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeAuth extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_auth';
    }

    public function rules()
    {
        return [
            [['user_id', 'password_hash'], 'required'],
            [['user_id', 'password_hash', 'password_reset_token'], 'string', 'max'=>255],
            [['user_id'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id'=>'User ID',
            'password_hash'=>'Password Hash',
            'password_reset_token'=>'Password Reset Token'
        ];
    } 
    
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }
}