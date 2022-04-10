<?php
namespace app\models\data;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\EmployeePosition;

class Organization extends ActiveRecord
{    
    public static function tableName()
    {
        return 'data_org';
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
            [['name', 'city', 'inn', 'kpp', 'ogrn', 'address'], 'required', 'on'=>'edit'],
            [['name'], 'required'],
            [['is_hidden', 'is_santal', 'status', 'created_at'], 'integer'],
            [['name', 'city', 'inn', 'kpp', 'ogrn', 'address', 'director'], 'string', 'max'=>255],
            [['inn'], 'match', 'pattern'=>'/^\d{10}$/', 'message'=>'Значение «{attribute}» должно содержать 10 цифр.'],              
            [['kpp'], 'match', 'pattern'=>'/^\d{9}$/', 'message'=>'Значение «{attribute}» должно содержать 9 цифр.'],
            [['inn'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование организации',
            'city'=>'Город',
            'inn'=>'ИНН',
            'kpp'=>'КПП',
            'ogrn'=>'ОГРН',
            'address'=>'Юр. Адрес',
            'director'=>'Руководитель',
            'is_santal'=>'САНТАЛЬ-ЦСМ',
            'status'=>'Статус',
            'created_at'=>'Дата регистрации'
        ];
    }
    
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['org_id'=>'id']);
    }   

    public function getPositions()
    {
        return $this->hasMany(EmployeePosition::className(), ['org_id'=>'id']);
    }        
}