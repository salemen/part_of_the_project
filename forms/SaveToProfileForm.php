<?php
// Форма сохранение данных в ЛК

namespace app\forms;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class SaveToProfileForm extends Model
{
    public $email;
    public $phone;
    public $params;
    public $validate;    

    public function rules()
    {
        return [
            [['email', 'phone'], 'string', 'max'=>255],
            [['email'], 'email', 'message'=>'E-mail введен неверно.'],
            [['phone'], 'match', 'pattern'=>'/\d{11}/', 'message'=>'«{attribute}» введен неверно.'],
            [['params'], 'safe'],
            [['validate'], 'validateEither', 'skipOnEmpty'=>false]            
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'email'=>'E-mail',
            'phone'=>'Номер телефона'
        ];
    }
    
    public function save()
    {
        if (!$this->validate()) { return null; }
        
        $user = $this->getUser(); 
        
        if ($user !== null) {            
            return true;            
        }
        
        return null;
    }  
    
    public function validateEither($attribute)
    {
        if (!$this->hasErrors()) {
            if (empty($this->email) && empty($this->phone)) {      
                $this->addError('email');
                $this->addError('phone');
                $this->addError($attribute, 'Заполните E-mail и/или Номер телефона.');
            }
        }
    } 
    
    protected function getUser()
    {
        $user = Yii::$app->user;
        
        if ($user !== null) {
            return $user;
        }
        
        throw new UnauthorizedHttpException('Вам запрещено выполнять данное действие. Пожалуйста авторизуйтесь.');
    } 
    
    protected function saveParams($user)
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        $model = null;
        
        if (isset($this->params['height'])) {
            $model->height = $this->params['height'];
        }        
        if (isset($this->params['weight'])) {
            $model->weight = $this->params['weight'];
        }
        if (isset($this->params['hip'])) {
            $model->hip = $this->params['hip'];
        }
        if (isset($this->params['waist'])) {
            $model->waist = $this->params['waist'];
        }

        if ($model->save()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Не удалось сохранить данные.');
        }
    }        
}