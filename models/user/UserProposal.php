<?php
namespace app\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\proposal\ProposalCallDoctor;

class UserProposal extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ONHOLD = 5;
    const STATUS_ONWORK = 10;
    const STATUS_SUCCESS = 20;
    
    const TYPE_CALL_DOCTOR = 10;  // вызов рача на дом
    
    public static function tableName()
    {
        return 'user_proposal';
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
            [['type_id', 'user_id'], 'required'],
            [['type_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['user_id', 'param1', 'param2', 'updated_by'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'type_id'=>'Тип заявки',
            'user_id'=>'Пациент',
            'param1'=>'Параметр 1',
            'param2'=>'Параметр 2',
            'comment'=>'Комментарий',
            'status'=>'Статус заявки',
            'created_at'=>'Дата добавления',
            'updated_at'=>'Дата обновления',
            'updated_by'=>'Заявку обработал(а)'
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'user_id']);
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'user_id']);
    }
    
    public function getProposalBlank()
    {
        return $this->hasOne(ProposalCallDoctor::className(), ['proposal_id'=>'id']);
    }
    
    public function getUpdater()
    {
        return $this->hasOne(Employee::className(), ['id'=>'updated_by']);
    }
}