<?php
namespace app\models\monitor;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MonitorProtocolOrvi extends ActiveRecord
{
    const STATUS_DEFAULT = 0;
    const STATUS_SUCCESS = 10;
    const STATUS_WARNING = 20;
    const STATUS_DANGER = 30;

    public static function tableName()
    {
        return 'monitor_protocol_orvi';
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
            [['passport_id', 'p_temp', 'p_chast', 'p_tyazh', 'p_bolmysh', 'p_bolgorlo', 'p_diarea', 'p_toshn', 'p_bolgolova', 'p_slab', 'p_limf', 'p_zapah', 'p_lek_vir', 'p_lek_antib', 'p_lek_zhar', 'p_feel'], 'required'],
            [['passport_id', 'result', 'status', 'created_at','covid'], 'integer'],
            [['p_pulsmetr', 'p_temp', 'p_kash', 'p_kash_type', 'p_odishka', 'p_chast', 'p_tyazh', 'p_bolmysh', 'p_bolgorlo', 'p_diarea', 'p_toshn', 'p_bolgolova', 'p_slab', 'p_limf', 'p_zapah', 'p_lek_vir', 'p_lek_antib', 'p_lek_zhar', 'p_feel'], 'string', 'max'=>255],
            [['complain'], 'string', 'max'=>1000],
            [['p_temp'], 'match', 'pattern'=>'/\d{2}.\d{1}/', 'message'=>'«{attribute}» должна быть в виде ХХ.Х.'],
            [['p_kash'], 'validateKashType'],
            [['p_temp'], 'validateTemp'],
            [['p_pulsmetr'], 'in', 'range'=>range(0, 100), 'message'=>'Введите значение от 0 до 100.'],
            [['p_kash'], 'in', 'range'=>range(0, 60), 'message'=>'Введите значение от 0 до 60.'],
            [['p_chast'], 'in', 'range'=>range(16, 60), 'message'=>'Введите значение от 16 до 60.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'covid'=>'Подтвержденный тест на COVID19',
            'passport_id'=>'ID паспорта',
            'p_temp'=>'Температура, °C',
            'p_pulsmetr'=>'Оксигенация крови кислородом (при наличии пульсоксиметра), %',
            'p_kash'=>'Кашель, раз/сутки',
            'p_kash_type'=>'Характер кашля',
            'p_chast'=>'Частота дыхания, ЧДД/минуту',
            'p_tyazh'=>'Тяжесть в груди',
            'p_bolmysh'=>'Боль в мышцах',
            'p_bolgorlo'=>'Боль в горле',
            'p_diarea'=>'Диарея, раз/сутки',
            'p_toshn'=>'Тошнота',
            'p_odishka'=>'Одышка',
            'p_bolgolova'=>'Головная боль',
            'p_slab'=>'Слабость',
            'p_limf'=>'Увеличенные лимфоузлы',
            'p_zapah'=>'Потеря обоняния (перестал различать запахи)',
            'p_lek_vir'=>'Прием противовирусных препаратов',
            'p_lek_antib'=>'Прием антибиотиков',
            'p_lek_zhar'=>'Прием жаропонижающих препаратов',
            'p_feel'=>'Самочувствие',
            'complain'=>'Другие жалобы',
            'result'=>'Результат, баллов',
            'status'=>'Клинический статус',
            'created_at'=>'Дата (время московское)'
        ];
    }

    public function validateTemp($attribute)
    {
        if ($this->p_temp) {
            $range = range(30, 45, 0.1);
            $temp = (double)$this->p_temp;
            if (!in_array($temp, $range)) {
                $this->addError($attribute, "Введите значение от 30.0 до 45.0 °C.");
            }
        }
    }

    public function validateKashType()
    {
        if ($this->p_kash && $this->p_kash_type == null) {
            $label = $this->getAttributeLabel('p_kash_type');
            $this->addError('p_kash_type', "Необходимо заполнить «{$label}».");
        }
    }

    public function getPassport()
    {
        return $this->hasOne(MonitorPassport::className(), ['id'=>'passport_id']);
    }

    public static function getValues()
    {
        return [
            'p_kash_type'=>[
                'влажный'=>'Влажный',
                'сухой'=>'Сухой'
            ],
            'p_odishka'=>[
                'нет'=>'Нет',
                'есть'=>'Есть'
            ],
            'p_tyazh'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_bolmysh'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_bolgorlo'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_toshn'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_bolgolova'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_slab'=>[
                'отсутствует'=>'Отсутствует',
                'легкое'=>'Легкое',
                'выраженное'=>'Выраженное'
            ],
            'p_limf'=>[
                'нет'=>'Нет',
                'да'=>'Да'
            ],
            'p_zapah'=>[
                'нет'=>'Нет',
                'да'=>'Да'
            ],
            'p_lek_vir'=>[
                'нет'=>'Нет',
                'да'=>'Да'
            ],
            'p_lek_antib'=>[
                'нет'=>'Нет',
                'да'=>'Да'
            ],
            'p_feel'=>[
                'хорошее'=>'Хорошее',
                'удовлетворительное'=>'Удовлетворительное',
                'плохое'=>'Плохое'
            ]
        ];
    }

    public static function isExists($passport_id)
    {
        return self::find()->where(['passport_id'=>$passport_id])->exists();
    }
}