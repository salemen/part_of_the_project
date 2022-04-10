<?php
namespace app\models\payments;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PaymentsOnline extends ActiveRecord
{
    const STATUS_CANCEL = 0;
    const STATUS_PAYD = 10;
    
    public static function tableName()
    {
        return 'payments_online';
    }
    
    public function afterSave($insert, $changedAttributes)
    {       
        if ($insert) {
            $this->updateOrderStatus($this->service_id);
        }
        
        parent::afterSave($insert, $changedAttributes);
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
            [['is_test', 'status', 'created_at'], 'integer'],
            [['invoice_id', 'service_id', 'user_id'], 'string', 'max'=>255],
            [['pay_amount', 'pay_result', 'pay_paycash', 'pay_type'], 'string', 'max'=>32]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'invoice_id'=>'Уникальный номер',
            'service_id'=>'Заказ',
            'user_id'=>'Пациент',
            'pay_amount'=>'Сумма платежа',
            'pay_result'=>'Доход',
            'pay_paycash'=>'Код валюты',
            'pay_type'=>'Способ платежа',
            'is_test'=>'Тест',
            'status'=>'Статус',
            'created_at'=>'Дата платежа'
        ];
    }
    
    protected function updateOrderStatus($key)
    {
        $client = Yii::$app->odata->connect();
        $model = $client->{"InformationRegister_СтатусыУслуг"}->filter("Заказ_Key eq guid'{$key}'")->get()->values();
        
        if ($model) {
            foreach ($model as $el) {
                $client->{"InformationRegister_СтатусыУслуг"}->update($el['УникальныйИдентификаторУслуги'], [
                    'НаОплату'=>false,
                    'НомерКвитанцииОнЛайн'=>$this->invoice_id,
                    'ДатаОплатыОнЛайн'=>date('Y-m-d\TH:i:s')
                ]);
            }
        }
    }
    
    public static function isExists($service_id)
    {
        return self::find()->where(['service_id'=>$service_id, 'is_test'=>false, 'status'=>self::STATUS_PAYD])->exists();
    }        
}