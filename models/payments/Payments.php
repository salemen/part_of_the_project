<?php
namespace app\models\payments;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\patient\Patient;

class Payments extends ActiveRecord
{
    const STATUS_CANCEL = 0;
    const STATUS_PAYD = 10;
    const STATUS_RETURNED = 20;
    
    const TYPE_TEST = 0;
    const TYPE_CONSULT = 10;
    const TYPE_CARDIOGRAM = 20;
    
    public static function tableName()
    {
        return 'payments';
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'orderCreatedDatetime',
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['invoiceId', 'customerNumber', 'orderNumber', 'orderSumAmount', 'orderSumCurrencyPaycash', 'paymentType', 'shopSumAmount'], 'required'],
            [['orderCreatedDatetime', 'orderType', 'orderNumber', 'orderStatus', 'isTest', 'status1c'], 'integer'],
            [['invoiceId', 'orderComment', 'orderSumAmount', 'orderSumCurrencyPaycash', 'paymentType', 'shopSumAmount'], 'string', 'max'=>32],
            [['customerNumber', 'comment1c'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'invoiceId'=>'Уникальный номер',
            'customerNumber'=>'Пациент',
            'orderCreatedDatetime'=>'Дата платежа',
            'orderStatus'=>'Статус',
            'orderType'=>'Тип услуги',
            'orderNumber'=>'Назначение платежа',  
            'orderComment'=>'Комментарий',
            'orderSumAmount'=>'Сумма платежа',
            'orderSumCurrencyPaycash'=>'Код валюты',
            'paymentType'=>'Способ платежа',
            'shopSumAmount'=>'Доход',
            'isTest'=>'Тестовый платеж',
            'status1c'=>'Статус обработки 1С',
            'comment1c'=>'Комментарий обработки 1С'
        ];
    }
    
    public function getConsultRelation()
    {
        return $this->hasOne(self::className(), ['id'=>'id'])
            ->andWhere(['orderType'=>self::TYPE_CONSULT]);
    }
    
    public function getConsult()
    {
        return $this->hasOne(Consult::className(), ['id'=>'orderNumber'])
            ->via('consultRelation');
    }
    
    public function getEmployeeConsult()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id'])
            ->viaTable(Consult::tableName(), ['id'=>'orderNumber']);
    } 
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'customerNumber']);
    }
    
    public function getEmployeePatient()
    {
        return $this->hasOne(Employee::className(), ['id'=>'customerNumber']);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'customerNumber']);
    }  
}