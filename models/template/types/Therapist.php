<?php
namespace app\models\template\types;

use Yii;
use yii\db\ActiveRecord;

class Therapist extends ActiveRecord
{    
    public $view = 'therapist';
    
    public static function tableName()
    {
        return 'template_therapist';
    }

    public function rules()
    {
        return [
            [['сomplaints', 'anamnez', 'nalet', 'hripiloc', 'bowel', 'swelling', 'otherdata', 'diagnosis', 'recommendation', 'exploration'], 'string'],
            [['template_id', 'pulse', 'liverlen', 'lulen'], 'integer'],
            [['pulse'], 'integer', 'min'=>20, 'max'=>220],
            [['self', 'health', 'night', 'skin', 'appetite', 'lu', 'lutype', 'zeva', 'mindalini', 'dugi', 'nbreath', 'nbreathval', 'nbreathtype', 'lbreath', 'hripi', 'theart', 'pulseritme', 'tongue', 'stomach', 'onpulp', 'morbidity', 'othermorbidity', 'liver', 'sidesolidity', 'sidepain', 'toilette', 'toiletterate', 'diuresis', 'docnum', 'docnum_from', 'docnum_to', 'nextvisit'], 'string', 'max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'template_id'=>'Бланк осмотра',
            'сomplaints'=>'Жалобы',
            'anamnez'=>'Анамнез заболевания',
            'health'=>'Состояние',
            'self'=>'Самочувствие ',
            'night'=>'Сон',
            'skin'=>'Кожа',
            'appetite'=>'Аппетит',
            'lu'=>'Л/у',
            'lulen'=>'до, см',
            'lutype'=>'Вид л/у',
            'zeva'=>'Слизистая зева',
            'mindalini'=>'Миндалины',
            'nalet'=>'Налет',
            'dugi'=>'Дужки',
            'nbreath'=>'Носовое дыхание',
            'nbreathval'=>'Отделяемое в ',
            'nbreathtype'=>'Характер',
            'lbreath'=>'Дыхание в легких',
            'hripi'=>'Хрипы',
            'hripiloc'=>'Хрипы в',
            'theart'=>'Тоны сердца',
            'pulse'=>'Пульс',
            'pulseritme'=>'Ритм пульса',
            'tongue'=>'Язык',
            'stomach'=>'Живот',
            'onpulp'=>'При пальпации',
            'morbidity'=>'Болезненность',
            'othermorbidity'=>'Другое',
            'liver'=>'Печень',
            'liverlen'=>'на, см',
            'sidesolidity'=>'Край',
            'sidepain'=>'Край',
            'bowel'=>'Кишечник',
            'toilette'=>'Стул',
            'toiletterate'=>'Частота стула',
            'diuresis'=>'Диурез',
            'swelling'=>'Отеки',
            'otherdata'=>'Дополниетельные данные',
            'diagnosis'=>'Диагноз',
            'recommendation'=>'Рекомендации',
            'exploration'=>'Обследование',
            'docnum'=>'Б/л (справка) №',
            'docnum_from'=>'с',
            'docnum_to'=>'по',
            'nextvisit'=>'Очередная явка'
        ];
    }
    
    public function recombine()
    {
        foreach ($this->getValues() as $key=>$val) {
            if (is_array($val)) {
                $this->$key = (strlen($this->$key) !== 0) ? $val[(int)($this->$key)] : null;
            }
        }
        
        return true;
    }

    public function getValue($name, $value)
    {
        return array_search($value, $this->getValues()[$name]);
    }

    public function getValues()
    {
        return [
            'self'=>['не страдает', 'плохое'],
            'health'=>['удовлетворительное', 'отн.удовлетворительное', 'средней тяжести', 'тяжелое'],
            'night'=>['спокойный', 'тревожный', 'повышенная сонливость'],
            'skin'=>['чистая', 'с элементами сыпи', 'с элементами шелушения'],
            'appetite'=>['снижен', 'не снижен'],
            'lu'=>['не увеличены', 'увеличены'],
            'lutype'=>['шейные', 'затылочные', 'подчелюстные', 'подмышечные', 'паховые'],
            'zeva'=>['розовая', 'чистая', 'умеренная гиперемия', 'яркая гиперемия'],
            'mindalini'=>['интактны', 'увеличены', 'рыхлые'],
            'dugi'=>['гиперемированы', 'отечны', 'интактны'],
            'nbreath' =>['свободное', 'затруднено'],
            'nbreathval' =>['скудном', 'умеренном', 'обильном'],
            'nbreathtype' =>['серозного', 'серозно-слизистого', 'слизистого', 'слизисто-гнойного'],
            'lbreath' =>['везикулярное', 'пуэрильное', 'жесткое', 'ослабленное'],
            'hripi' =>['нет', 'проводные', 'сухие', 'влажные', 'мелкопузырчатые'],
            'theart' =>['громкие', 'ритмичные', 'ясные', 'систолический шум', 'приглушенные'],
            'pulseritme' =>['ритмичный', 'наполнения и напряжения удовлетворительного', 'хорошего напряжения'],
            'tongue'=>['чистый', 'влажный', 'обложен белым', 'желтовато-белым налетом'],
            'stomach'=>['мягкий', 'вздут'],
            'onpulp'=>['разлитая', 'ограниченная'],
            'morbidity'=>['в эпигастрии, в средней и нижней трети живота', 'справа по средней линии', 'слева по средней линии', 'в правом подреберье', 'зоне Шоффара'],
            'liver'=>['Выступает', 'Не выступает'],
            'sidesolidity'=>['уплотненный', 'не уплотненный'],
            'sidepain'=>['болезненный', 'не болезненный'],
            'toilette'=>['оформленный', 'разжиженный', 'жидкий'],
            'toiletterate'=>['регулярный', 'частый'],
            'diuresis'=>['норма', 'учащен', 'болезненный']
        ];
    }    
}