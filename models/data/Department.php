<?php
namespace app\models\data;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Department extends ActiveRecord
{
    public static function tableName()
    {
        return 'data_dep';
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
            [['name', 'address', 'short_address', 'org_id'], 'required'],
            [['org_id', 'is_santal', 'status', 'created_at'], 'integer'],
            [['name', 'address', 'short_address', 'alias'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование подразделения',
            'address'=>'Адрес',
            'short_address'=>'Адрес (сокращенно)',
            'alias'=>'Алиас',
            'org_id'=>'Организация',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id'=>'org_id']);
    }        
}