<?php
// Форма для навигации по сайту через укороченные ссылки

namespace app\forms;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\other\ShortLinks;

class GoForm extends Model
{
    public $url;
    
    private $_data;
    private $_user;
    
    public function __construct($hash, $config = [])
    {
        if (empty($hash) || !is_string($hash)) {
            throw new InvalidArgumentException('Hash cannot be blank.');
        }
        
        $this->_data = ShortLinks::findOne(['hash'=>$hash]);
        if (!$this->_data) {
            throw new InvalidArgumentException('Wrong Hash.');
        }        
        
        $user_id = $this->_data->user_id;
        
        $this->_user = $this->getUser($user_id);
        if (!$this->_user) {
            throw new InvalidArgumentException('User not found.');
        }
        
        $this->url = $this->_data->url;
        
        parent::__construct($config);
    }
    
    public function login()
    {
        $user = $this->_user;
        
        if (Yii::$app->user->isGuest) {
            if ($user instanceof Employee) {
                Yii::$app->session->set('employee_santal', true);
            }
            return Yii::$app->user->login($user);
        } else {
            if (Yii::$app->user->identity->id !== $user->id) {
                Yii::$app->user->logout();
                return false;                
            }
            
            return true;
        }        
    }       
    
    protected function getUser($user_id)
    {
        $model = null;
        
        if ($model === null) {
            $model = Employee::findOne($user_id);
        }
        
        if ($model === null) {
            $model = Patient::findOne($user_id);
        }
        
        return $model;
    }        
}