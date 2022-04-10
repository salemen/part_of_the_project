<?php
namespace app\models\data;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class OrganizationBank extends ActiveRecord
{
    public static function tableName()
    {
        return 'data_org_bank';
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
            [['org_id', 'bank', 'bik', 'check_c', 'check_r'], 'required'],
            [['org_id', 'created_at'], 'integer'],
            [['bank', 'inn', 'kpp', 'ogrn', 'bik', 'check_c', 'check_r'], 'string', 'max'=>255],
            [['org_id', 'check_r'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'org_id'=>'Org ID',
            'bank'=>'Наименование банка',
            'inn'=>'ИНН',
            'kpp'=>'КПП',
            'ogrn'=>'ОГРН',
            'bik'=>'БИК',
            'check_c'=>'Кор. счет',
            'check_r'=>'Рас. счет',
            'created_at'=>'Дата'
        ];
    }
    
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id'=>'org_id']);
    }      
    
    public static function isExists($org_id)
    {
        return self::find()->where(['org_id'=>$org_id])->exists();
    }        
}