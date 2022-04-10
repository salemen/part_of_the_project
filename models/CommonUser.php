<?php
namespace app\models;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;
use yii\web\User;
use app\models\cron\CronNotification;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\employee\EmployeePosition;

class CommonUser extends User
{    
    public function getIsAdvisor()
    {
        $model = Yii::$app->user;
        
        return ($model->isGuest) ? false : (isset($model->identity->advisor) && $model->identity->advisor !== null);
    }
    
    public static function getPhoto($user_id = false)
    {
        if ($user_id === false) {
            $user = Yii::$app->user;
            $user_id = ($user->isGuest) ? null : $user->id;
        }
        
        $model = null;
        
        if ($model === null) {
            $model = Employee::findOne($user_id);            
        }
        
        if ($model === null) {
            $model = Patient::findOne($user_id);            
        }

        if ($model) {
            return Employee::getProfilePhoto($model);
        }

        
    }
    
    public static function getUserById($id)
    {
        $model = null;
        
        if ($model === null) {
            $model = Employee::findOne($id);
        }    
        
        if ($model === null) {
            $model = Patient::findOne($id);
        }
        
        return $model;
    }
    
    public static function getUser($params = [])
    {        
        $model = null;
        
        $email = isset($params['email']) && $params['email'] !== '' ? trim($params['email']) : null;
        $phone = isset($params['phone']) ? self::parsePhone($params['phone']) : null;
        $snils = isset($params['snils']) ? trim($params['snils']) : null;        
        
        if ($model === null) {
            $model = Employee::findByPhone($phone);
            if ($model === null) {
                $model = Employee::findByEmail($email);
                if ($model === null) {
                    $model = Employee::findBySnils($snils);
                }
            }
        }
        
        if ($model === null) {
            $model = Patient::findByPhone($phone);
            if ($model === null) {
                $model = Patient::findByEmail($email);
                if ($model === null) {
                    $model = Patient::findBySnils($snils);
                }
            }
        }
        
        return $model;
    }
    
    public static function getUsers()
    {
        $columns = ['id', 'fullname', 'snils', 'user_birth', 'phone', 'email'];
        
        $empl = Employee::find()->select($columns)->where(['status'=>10])->asArray()->all();
        $pat = Patient::find()->select($columns)->where(['status'=>10])->asArray()->all();
        
        $users = ArrayHelper::merge($empl, $pat);
        ArrayHelper::multisort($users, 'fullname');
        
        return $users;        
    }        
    
    public static function createUser($params = [], $notify = true)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $password = sprintf("%06d", rand(1, 999999));
            
        $model = new Patient();
        $model->guid = isset($params['guid']) ? trim($params['guid']) : null;
        $model->email = isset($params['email']) ? trim($params['email']) : null;
        $model->fullname = isset($params['fullname']) ? mb_convert_case($params['fullname'], MB_CASE_TITLE, 'utf-8') : 'Аноним';
        $model->snils = isset($params['snils']) ? $params['snils'] : null;
        $model->phone = isset($params['phone']) ? self::parsePhone($params['phone']) : null;
        $model->city = isset($params['city']) ? $params['city'] : null;
        $model->user_birth = isset($params['user_birth']) ? $params['user_birth'] : null;
        $model->sex = isset($params['sex']) ? $params['sex'] : null;
        $model->generateAuthKey();
        $model->generateId();
        $model->setPassword($password);
                
        if (!$model->save()) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Ошибка при сохранении данных пользователя.');
        }
        
        if ($notify == 'true' && !self::saveNotification($model, $password)) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Ошибка при сохранении данных сообщения.');
        }
        
        $transaction->commit();        
        return $model;
    }
    
    public static function updateUser($model, $params = [])
    {
        if ($model && $model instanceof Patient) {
            $transaction = Yii::$app->db->beginTransaction();
        
            if (isset($params['guid'])) { $model->guid = trim($params['guid']); }
            if (isset($params['fullname']) && $model->fullname === 'Аноним') { $model->fullname = mb_convert_case($params['fullname'], MB_CASE_TITLE, 'utf-8'); }
            if (isset($params['snils']) && !$model->snils) { $model->snils = $params['snils']; }
            if (isset($params['city']) && !$model->city) { $model->city = $params['city']; }
            if (isset($params['email']) && !$model->email) { $model->email = trim($params['email']); }
            if (isset($params['user_birth']) && !$model->user_birth) { $model->user_birth = $params['user_birth']; }

            if (!$model->save()) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при обновлении данных пользователя.');
            }

            $transaction->commit();
        }
        
        return $model;
    }
    
    public static function saveNotification($model, $password)
    {
        $login = $model->username;
        $phone = $model->phone;        
        
        $message = "0323.ru. Логин: {$login}, Телефон: {$phone}, Пароль: {$password}";
        
        return (new CronNotification([
            'target'=>$model->id,
            'message'=>base64_encode($message),
            'type'=>CronNotification::TYPE_NEW_USER
        ]))->save();
    }
    
    public static function parsePhone($phone)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phoneProto = $phoneUtil->parse($phone);
        
        return $phoneUtil->format($phoneProto, PhoneNumberFormat::E164);
    }
    
    public static function isProfileValid($user_id)
    {
        $model = self::getUserById($user_id);
                
        if ($model !== null) {
            if ($model instanceof Patient) {
                if ($model->fullname === 'Аноним') {
                    return false;
                }
                if ($model->city === null) {
                    return false;
                }                
                if ($model->sex === null) {
                    return false;
                }
                if ($model->user_birth === null) {
                    return false;
                }
            }            
        }      
        
        return true;
    }
}