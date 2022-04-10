<?php
namespace app\models\monitor;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\user\UserData;
use app\models\monitor\MonitorProtocolOrvi;

class MonitorPassport extends ActiveRecord
{
    public $user_f;
    public $user_i;
    public $user_o;
    public $user_birth;
    public $city;
    public $address;
    public $clinic;

    const TYPE_ORVI = 10;

    const STATUS_DEFAULT = 0;
    const STATUS_SUCCESS = 10;
    const STATUS_WARNING = 20;
    const STATUS_DANGER = 30;

    public static function tableName()
    {
        return 'monitor_passport';
    }

    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className()
            ]
        ];
    }
    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_birth', 'city', 'address', 'reason'], 'required', 'on'=>'passport-create'],
            [['user_id', 'reason', 'period_start', 'period_end', 'protocol_type'], 'required'],
            [['passport_status', 'protocol_status', 'protocol_type', 'result', 'is_checked', 'is_to_doc', 'is_end', 'is_archive', 'checked_at', 'created_at', 'updated_at'], 'integer'],
            [['user_f', 'user_i', 'user_o', 'user_birth', 'city', 'address', 'user_id', 'reason', 'clinic', 'period_start', 'period_end','motive'], 'string', 'max'=>255],
            [['sicks'], 'safe'],
            [['user_birth'], 'match', 'pattern'=>'/\d{2}.\d{2}.\d{4}/', 'message'=>'«{attribute}» должна быть в виде ХХ.ХX.XXXX']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'',
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_birth'=>'Дата рождения',
            'city'=>'Город',
            'address'=>'Адрес места жительства',
            'user_id'=>'Пациент',
            'reason'=>'Причины постановки на учет',
            'sicks'=>'Сопутствующие заболевания',
            'clinic'=>'Моя поликлиника',
            'period_start'=>'Дата постановки на учет',
            'period_end'=>'Дата снятия с учета',
            'passport_status'=>'Эпидемический статус',
            'protocol_status'=>'Клинический статус',
            'protocol_type'=>'Тип протокола',
            'result'=>'Результат, баллов',
            'is_checked'=>'Отметка об исполнении',
            'is_to_doc'=>'Передано врачу',
            'is_end'=>'Работа с пациентом завершена',
            'is_archive'=>'Архив',
            'motive'=>'Причина переноса в архив',
            'checked_at'=>'Дата исполнения',
            'created_at'=>'Дата добавления',
            'updated_at'=>'Дата последнего заполнения',
        ];
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'user_id']);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'user_id']);
    }

    public function getData()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'user_id']);
    }

    public function getProtocols()
    {
        return $this->hasOne(MonitorProtocolOrvi::className(), ['passport_id'=>'id']);
    }

    public static function getValues()
    {
        return [
            'reason'=>[
                20=>'Признаки ОРВИ без подтвержденного теста ПЦР на Covid -19',
                30=>'Контакт с ковид-больным',
                60=>'Положительный ПЦР на ковид'
            ]
        ];
    }

    public static function isNotActive($id)
    {
        $model = self::findOne($id);
        $lastActivity = MonitorProtocolOrvi::find()->where(['passport_id'=>$model->id])->max('created_at');
        $result = false;

        if ($lastActivity !== null) {
            switch ($model->protocol_status) {
                case self::STATUS_DEFAULT:
                    $result = true;
                    break;
                case self::STATUS_SUCCESS:
                    $result = (date('U') - 86400 * 2) > $lastActivity;
                    break;
                case self::STATUS_WARNING:
                    $result = (date('U') - 86400) > $lastActivity;
                    break;
                case self::STATUS_DANGER:
                    $result = (date('U') - 86400 / 2) > $lastActivity;
                    break;
            }
        }

        return $result;
    }
}