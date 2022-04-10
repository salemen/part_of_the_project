<?php
namespace app\models\pz;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\AppHelper;

class RentgenJurnal extends ActiveRecord
{
    public static function tableName()
    {
        return 'rentgen_jurnal';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = Yii::$app->user->identity;
            
            if ($this->isNewRecord) {
                $this->r_data = date('Y-m-d H:i:s');
                $this->r_vrach = AppHelper::shortFullname($user->fullname);
            } else {
                if ($this->r_sakl_data === null) {
                    $this->r_sakl_data = date('Y-m-d H:i:s');
                }
                if ($this->r_sakl_vrach === null) {
                    $this->r_sakl_vrach = AppHelper::shortFullname($user->fullname);
                }
                $this->r_norm_group = 1;
            }
            
            return true;
        } else {
            return false;
        }
    }

    public static function getDb()
    {
        return Yii::$app->get('db_pz');
    }

    public function rules()
    {
        return [
            [['r_fio_id'], 'required'],
            [['r_sakl', 'r_norm_group'], 'required', 'on'=>'update'],
            [['r_fio_id', 'r_num_snimk', 'r_visible', 'r_norm_group'], 'integer'],
            [['r_data', 'r_sakl_data'], 'safe'],
            [['r_organis', 'r_obl_issled', 'r_eed', 'r_vrach', 'r_sakl_vrach'], 'string', 'max'=>256],
            [['r_diagnos', 'r_sakl', 'comment'], 'string', 'max'=>1000],
            [['r_sakl_opis'], 'string'],
            [['r_n_medk', 'r_o_group', 'r_paytype'], 'string', 'max'=>10]
        ];
    }

    public function attributeLabels()
    {
        return [
            'r_id'=>'#',
            'r_fio_id'=>'Пациент',
            'r_n_medk'=>'ID Пациента',
            'r_organis'=>'Организация',
            'r_diagnos'=>'Диагноз',
            'r_obl_issled'=>'Область исследования',
            'r_eed'=>'ЭЭД (значение)',
            'r_num_snimk'=>'Количество снимков',
            'r_vrach'=>'Запись добавил(а)',
            'r_data'=>'Дата записи',
            'r_o_group'=>'Подразделение',
            'r_paytype'=>'Метод оплаты',
            'r_sakl_data'=>'Дата описания',
            'r_sakl_vrach'=>'Заключение составил(а)',
            'r_sakl_opis'=>'Описание врача',
            'r_sakl'=>'Заключение врача',
            'r_norm_group'=>'Результат',
            'comment'=>'Пометка (комментарий)'
        ];
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['u_id'=>'r_fio_id']);
    }
}