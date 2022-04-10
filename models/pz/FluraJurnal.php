<?php
namespace app\models\pz;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\AppHelper;

class FluraJurnal extends ActiveRecord
{
    public static function tableName()
    {
        return 'flura_jurnal';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = Yii::$app->user->identity;
            
            if ($this->isNewRecord) {
                $this->f_data = date('Y-m-d H:i:s');
                $this->f_vrach = AppHelper::shortFullname($user->fullname);
            } else {
                if ($this->f_sakl_data === null) {
                    $this->f_sakl_data = date('Y-m-d H:i:s');
                }
                if ($this->f_sakl_vrach === null) {
                    $this->f_sakl_vrach = AppHelper::shortFullname($user->fullname);
                }                
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
            [['f_fio_id'], 'required'],
            [['f_num_snimk', 'f_sakl', 'f_norm_group'], 'required', 'on'=>'update'],
            [['f_fio_id', 'f_num_snimk', 'f_visible', 'f_norm_group'], 'integer'],
            [['f_data', 'f_sakl_data'], 'safe'],
            [['f_organis', 'f_obl_issled', 'f_vrach', 'f_sakl_vrach'], 'string', 'max'=>256],
            [['f_diagnos', 'f_sakl', 'comment'], 'string', 'max'=>1000],
            [['f_sakl_opis'], 'string'],
            [['f_n_medk', 'f_o_group'], 'string', 'max'=>10]
        ];
    }

    public function attributeLabels()
    {
        return [
            'f_id'=>'#',
            'f_fio_id'=>'Пациент',
            'f_n_medk'=>'Номер мед.карты',
            'f_organis'=>'Организация',
            'f_diagnos'=>'Диагноз',
            'f_obl_issled'=>'Область исследования',
            'f_num_snimk'=>'Количество снимков',
            'f_vrach'=>'Запись добавил(а)',
            'f_data'=>'Дата записи',
            'f_o_group'=>'Подразделение',
            'f_sakl_data'=>'Дата описания',
            'f_sakl_vrach'=>'Заключение составил(а)',
            'f_sakl_opis'=>'Описание врача',
            'f_sakl'=>'Заключение врача',
            'f_norm_group'=>'Результат',
            'comment'=>'Пометка (комментарий)'
        ];
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['u_id'=>'f_fio_id']);
    }
}