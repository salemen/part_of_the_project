<?php
namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;
use app\models\employee\Employee;

class UserData extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_data';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'address', 'passport', 'polis_oms_org', 'polis_oms_number', 'clinic'], 'string', 'max'=>255],
            [['user_id'], 'unique'],
            ['polis_oms_number', 'match','pattern'=> '/^[1234567890\s,]+$/']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id'=>'',
            'address'=>'Адрес места жительства',
            'passport'=>'Паспорт (серия, номер)',
            'polis_oms_org'=>'Полис ОМС (организация)',
            'polis_oms_number'=>'Полис ОМС (номер)',
            'clinic'=>'Поликлиника (прикрепление)'
        ];
    }

    public function getEmp()
    {
        return $this->hasOne(Employee::className(), ['id' => 'user_id']);
    }


}