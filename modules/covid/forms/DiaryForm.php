<?php
namespace app\modules\covid\forms;

use Yii;
use yii\base\Model;
use app\modules\covid\models\CovidDiaryAnswers;
use app\modules\covid\models\CovidDiaryCheck;

class DiaryForm extends Model
{
    public $actions;
    public $actions_other;
    public $claims;
    public $claims_other;
    public $adds;
    public $comment;
    public $diary_id;
    
    const QUESTION_CLAIMS = 10;
    const QUESTION_ACTIONS = 20;

    public function rules()
    {
        return [
            [['actions', 'claims', 'adds', 'diary_id'], 'required'],
            [['actions_other', 'claims_other', 'comment'], 'string'],
            [['actions', 'claims', 'adds', 'diary_id'], 'safe']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'claims_other'=>'Другие нарушения',
            'actions_other'=>'Другое',
            'comment'=>'Комментарий'
        ];
    }
    
    public function getCombineValues($key, $parentKey = null)
    {
        $array = ($parentKey !== null) ? $this->getValues()[$parentKey][$key] : $this->getValues()[$key];
        
        if ($array) {
            $values = array_values($array);            
            return array_combine($values, $values);
        }
        
        return [];
    }

    public function getValues()
    {
        return [
            'actions'=>[
                'Ничего',
                'Дополнительное лечение',
                'Вызов скорой',
                'Госпитализация и стационар',
                'Вызов врача поликлиники на дом',
                'Обращение к врачу поликлиники',
                'Прием медикаментов'
            ],
            'adds'=>[
                'analysis'=>[
                    'Нет',
                    'Да'
                ],
                'contact'=>[
                    'Нет',
                    'Да'
                ],
                'pregnant'=>[
                    'Нет',
                    'Да'
                ],                
                'travel'=>[
                    'Нет',
                    'Да'
                ]                
            ],
            'claims'=>[
                'common'=>[
                    'Нет',
                    'Повышение температуры',
                    'Озноб',
                    'Головная боль',
                    'Головокружение',
                    'Слабость',
                    'Недомогание',
                    'Чувство жара',
                    'Ломота (боль) в мышцах',
                    'Ломота (боль) в суставах',
                    'Боль в области грудной клетки',
                    'Боль в горле',
                    'Затруднение дыхания',
                    'Кашель',
                    'Насморк',
                    'Заложенность носа',
                    'Потливость',
                    'Снижение вкуса',
                    'Снижение аппетита',
                    'Тошнота',
                    'Рвота',
                    'Нарушение стула',
                    'Боль в животе',
                    'Аллергические реакции'
                ],
                'domestic'=>[
                    'Нет',
                    'Боль / дискомфорт в месте введения вакцины',
                    'Сыпь',
                    'Зуд',
                    'Отек в месте введения вакцины',
                    'Припухлость в месте инъекции',
                    'Покраснение кожи в месте инъекции'
                ],
                'heart'=>[
                    'Нет',
                    'Повышение давления',
                    'Понижение давления',
                    'Учащение сердцебиения',
                    'Урежение сердцебиения',
                    'Боли / дискомфорт в области сердца',
                    'Обморок или потеря сознания'
                ],
                'vision'=>[
                    'Отсутствуют',
                    'Мелькание мушек перед глазами',
                    'Боль в области глазных яблок',
                    'Нарушение восприятия цвета',
                    'Неприятные ощущения при ярком свете',
                    'Ухудшение четкости зрения'
                ]
            ]
        ];
    }
    
    public function save()
    {        
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $check = new CovidDiaryCheck(['diary_id'=>$this->diary_id]);
            
            if (!$check->save()) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при сохранении данных.');
            }
            
            foreach ($this->claims as $claimKey=>$claim) {
                foreach ($claim as $claimAnswer) {
                    $this->saveAnswers($transaction, $check->id, $claimKey, $claimAnswer);
                }
            }
            
            if ($this->claims_other) {
                $this->saveAnswers($transaction, $check->id, 'claims_other', $this->claims_other);
            }
            
            foreach ($this->actions as $actionsAnswer) {
                $this->saveAnswers($transaction, $check->id, 'claims_other', $actionsAnswer);
            }
            
            if ($this->actions_other) {
                $this->saveAnswers($transaction, $check->id, 'actions_other', $this->actions_other);
            }
            
            foreach ($this->adds as $addsKey=>$addsAnswer) {
                $this->saveAnswers($transaction, $check->id, $addsKey, $addsAnswer);
            }
            
            if ($this->comment) {
                $this->saveAnswers($transaction, $check->id, 'comment', $this->comment);
            }
            
            $transaction->commit();            
            return true;
        }
        
        return false;
    }
    
    public function saveAnswers($transaction, $check_id, $question_type, $answer)
    {
        $model = new CovidDiaryAnswers([
            'check_id'=>$check_id,
            'question_type'=>$question_type,
            'answer'=>$answer
        ]);
        
        if (!$model->save()) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Ошибка при сохранении данных.');
        }
    }
}