<?php
namespace app\models\pz;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\helpers\AppHelper;

class Patient extends ActiveRecord
{
    public static function tableName()
    {
        return 'patient';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->u_fam = AppHelper::ucfirst($this->u_fam);
            $this->u_ima = AppHelper::ucfirst($this->u_ima);
            $this->u_otc = ($this->u_otc) ? AppHelper::ucfirst($this->u_otc) : null;
            $this->u_code = $this->generateCode();
            $this->u_data_ros = date("Y-m-d", strtotime($this->u_data_ros));
            
            return true;
        } else {
            return false;
        }
    }

    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'u_data_reg',
                'updatedAtAttribute'=>false,
                'value'=>date('Y-m-d')
            ]
        ];
    }
    
    public static function getDb()
    {
        return Yii::$app->get('db_pz');
    }

    public function rules()
    {
        return [
            [['u_fam', 'u_ima', 'u_data_ros', 'u_pol'], 'required'],
            [['u_data_reg', 'u_data_ros'], 'safe'],
            [['u_visible'], 'integer'],
            [['u_code', 'u_fam', 'u_ima', 'u_otc', 'u_addr', 'u_mest_rab'], 'string', 'max'=>256],
            [['u_pol'], 'string', 'max'=>11],
            [['u_fam', 'u_ima', 'u_otc'], 'trim']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'u_id'=>'ID',
            'u_code'=>'Код пациента',
            'u_fam'=>'Фамилия',
            'u_ima'=>'Имя',
            'u_otc'=>'Отчество',
            'u_data_reg'=>'Дата регистрации',
            'u_visible'=>'Видимость',
            'u_pol'=>'Пол',
            'u_data_ros'=>'Дата рождения',
            'u_addr'=>'Адрес / Учреждение',
            'u_mest_rab'=>'Место работы'
        ];
    }
    
    protected function generateCode()
    {
        $f = mb_substr($this->u_fam, 0, 1, 'utf-8');
        $i = mb_substr($this->u_ima, 0, 1, 'utf-8');
        $o = mb_substr($this->u_otc, 0, 1, 'utf-8');
        $d = date("dmY", strtotime($this->u_data_ros));
        
        $result = implode('', [$f, $i, $o, $d]);
        
        return mb_strtoupper($result);
    }        
}