<?php
namespace app\models\user;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class UserAnalysisProposal extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_analysis_proposal';
    }
    
     public function behaviors()
    {
        return [
            'blameable'=>[
                'class'=>BlameableBehavior::className(),
                'updatedByAttribute'=>false
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }
    
    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_o', 'user_sex', 'user_year'], 'required'],
            [['count', 'is_end', 'created_at'], 'integer'],
            [['user_f', 'user_i', 'user_o', 'user_sex', 'user_year', 'created_by'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_sex'=>'Пол',
            'user_year'=>'Год рождения',
            'count'=>'Записей выгружено',
            'is_end'=>'Заявка выполнена',
            'created_at'=>'Дата',
            'created_by'=>'ID Пользователя'
        ];
    }
    
    public static function isExists($user_id, $active = true)
    {
        return self::find()->where(['is_end'=>!$active, 'created_by'=>$user_id])->exists();
    }
}