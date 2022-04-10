<?php
namespace app\models\zung;

use Yii;
use yii\db\ActiveRecord;

class ZungPatients extends ActiveRecord
{
    public static function tableName()
    {
        return 'patients';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_zung');
    }

    public function rules()
    {
        return [
            [['phone', 'reg_date', 'status'], 'required'],
            [['reg_date'], 'safe'],
            [['phone', 'password', 'surname', 'name', 'midname', 'status', 'role'], 'string', 'max'=>100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'phone'=>'Phone',
            'password'=>'Password',
            'reg_date'=>'Reg Date',
            'surname'=>'Surname',
            'name'=>'Name',
            'midname'=>'Midname',
            'status'=>'Status',
            'role'=>'Role'
        ];
    }
}