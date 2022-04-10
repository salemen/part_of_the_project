<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\web\ServerErrorHttpException;

class AnketaQuestion extends ActiveRecord
{
    const TYPE_MAIN = 0;
    const TYPE_ONE = 10;
    const TYPE_MULTI = 20;
    const TYPE_OPEN = 30;
    const TYPE_DATE = 40;
    
    public $answers;
    
    public static function tableName()
    {
        return 'anketa_question';
    }

    public function rules()
    {
        return [
            [['answers'], 'validateAnswers'],
            [['anketa_id', 'type', 'name'], 'required'],
            [['anketa_id', 'parent_id', 'type', 'position', 'is_skip', 'status', 'parent_answer_id'], 'integer'],
            [['name'], 'string'],
            [['answers'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'anketa_id'=>'ID анкеты',
            'parent_id'=>'Родитель',
            'parent_answer_id'=>'Ответ родителя, от которого зависит',
            'type'=>'Тип вопроса',
            'name'=>'Текст вопроса',
            'is_skip'=>'Ответ не обязателен',
            'status'=>'Статус',
            'answers'=>'Варианты ответа'
        ];
    }
    
    public function afterSave($insert, $changedAttributes)
    {       
        if ($this->answers) {
            foreach ($this->answers as $answer) {   
                $model = (AnketaAnswer::findOne($answer['id'])) ?  : new AnketaAnswer(['question_id'=>$this->id]);
                
                if ($model->load($answer, '') && $model->save()) {
                    continue;
                }
                throw new ServerErrorHttpException();
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $max_pos = self::find()->where(['anketa_id'=>$this->anketa_id])->max('position');
                $order = ($max_pos == null) ? 0 : $max_pos + 1;
                $this->updateAttributes(['position'=>$order]);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function beforeDelete()
    {
        $answers = $this->anketaAnswers;
        
        foreach ($answers as $answer) {
            $answer->delete();
        }
        
        return parent::beforeDelete();
    }
    
    public function validateAnswers($attribute)
    {
        $require = new RequiredValidator();

        foreach ($this->$attribute as $index=>$row) {    
            $error = null;  
            $require->validate($row['name'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][name]';
                $this->addError($key, 'Необходимо заполнить «Варианты ответа».');
            }
        }
        
        return true;
    } 
    
    public function getAnketaAnswers()
    {
        return $this->hasMany(AnketaAnswer::className(), ['question_id'=>'id']);
    }
    
    public function getTypeName()
    {
        foreach ($this->getTypes() as $type) {
            if ($this->type == $type['type_id']) {
                return $type['name'];
            }
        }
    }
    
    public static function getQuestionTypeById($id)
    {
        return static::find()->select('type')->where(['id'=>$id])->scalar();
    }
    
    public static function getTypes()
    {
        return [
            [
                'type_id'=>self::TYPE_MAIN,
                'name'=>'Родительский вопрос'
            ],
            [
                'type_id'=>self::TYPE_DATE,
                'name'=>'Вопрос на выбор даты'
            ],
            [
                'type_id'=>self::TYPE_OPEN,
                'name'=>'Вопрос открытого типа'
            ],            
            [
                'type_id'=>self::TYPE_ONE,
                'name'=>'Вопрос с выбором одного ответа'
            ],
            [
                'type_id'=>self::TYPE_MULTI,
                'name'=>'Вопрос c выбором нескольких ответов'
            ]            
        ];
    }
}