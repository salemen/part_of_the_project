<?php
namespace app\models\research;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class ResearchIndex extends ActiveRecord
{
    const GRADE_COL = 0;
    const GRADE_QUAL = 10;

    public static function tableName()
    {
        return 'research_index';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $max_pos = self::find()->where(['IS', 'parent_id', null])->andWhere(['type_id'=>$this->type_id])->max('position');
                $position = ($max_pos == null) ? 0 : $max_pos + 1;
                $this->updateAttributes(['position'=>$position]);
            }
            return true;
        } else {
            return false;
        }
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
            [['name', 'grade_id', 'method_id', 'type_id'], 'required'],
            [['rel_id', 'parent_id', 'grade_id', 'method_id', 'method_alt_id', 'type_id', 'is_group', 'position', 'status', 'created_at', 'updated_at'], 'integer'],
            [['interp_down', 'interp_up', 'comment'], 'string'],
            [['name', 'name_alt'], 'string', 'max'=>255],
            [['name'], 'unique', 'targetAttribute'=>['name', 'type_id'], 'message'=>'Такой показатель для выбранного вида исследования уже существует.'],
            [['rel_id'], 'unique', 'targetAttribute'=>['type_id', 'rel_id'], 'message'=>'Такое значение уже зянято.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Показатель',
            'name_alt'=>'Синоним(ы)',
            'rel_id'=>'Лабораторное значение',
            'parent_id'=>'Родитель',
            'grade_id'=>'Сравнительный метод',
            'method_id'=>'Метод исследования',
            'method_alt_id'=>'Альтернативный метод исследования',
            'interp_down'=>'Интерпретация (пониженный показатель)',
            'interp_up'=>'Интерпретация (повышенный показатель)',
            'comment'=>'Примечание',
            'type_id'=>'Вид исследования',
            'is_group'=>'Не интерпретировать',
            'position'=>'Сортировка',
            'status'=>'Статус',
            'created_at'=>'Дата добавления',
            'updated_at'=>'Дата обновления'
        ];
    }
    
    public function getMethod()
    {
        return $this->hasOne(ResearchMethod::className(), ['id'=>'method_id']);
    }    

    public function getMethodAlt()
    {
        return $this->hasOne(ResearchMethod::className(), ['id'=>'method_alt_id']);
    }
    
    public function getNormsCol()
    {
        return $this->hasMany(ResearchNormsCol::className(), ['index_id'=>'id']);
    }   
    
    public function getNormsQual()
    {
        return $this->hasMany(ResearchNormsQual::className(), ['index_id'=>'id']);
    }
    
    public function getRel()
    {
        return $this->hasOne(ResearchLabIndex::className(), ['id'=>'rel_id']);
    } 
    
    public function getType()
    {
        return $this->hasOne(ResearchType::className(), ['id'=>'type_id']);
    }
    
    public static function getDoubtNorm($type, $value)
    {
        $result = ($type === 'min') ? $value - ($value * 0.05) : $value + ($value * 0.05);
        
        return $result;
    }        
    
    public static function getInterpretation($index_id, $value, $unit_id, $sex, $age = null, $showLinks = true)
    {
        $model = self::findOne($index_id);
        $unit = ResearchUnit::findOne($unit_id);        
        $user = Yii::$app->user;
        
        if ($model) {
            $linkConsult = $showLinks ? Html::a(Html::img('/img/graph/consult-online.png', ['class'=>'img-responsive']), ['/doctor'], ['class'=>'col-md-6', 'target'=>'_blank']) . 
                Html::a(Html::img('/img/graph/consult-offline.png', ['class'=>'img-responsive']), 'https://330003.org', ['class'=>'col-md-6', 'target'=>'_blank']) : null;
            
            if ($model->grade_id == self::GRADE_COL) {
                $normsModel = ResearchNormsCol::findOne(['index_id'=>$index_id, 'unit_id'=>$unit_id]);
                $unitName = ResearchUnit::getUnitName($unit->id);
                $contentStart = Html::tag('p', "{$model->name}: {$value} {$unitName}");
                
                if (!$normsModel) {
                    $content = $contentStart . Html::tag('p', 'Расшифровка не найдена. Попробуйте позже.');
                    return [ 'actions'=>null, 'content'=>$content, 'type'=>'red' ];
                }
                
                switch ($sex) {
                    case 'man':
                        $norms = ['norm_m_min', 'norm_m_max'];
                        break;
                    case 'pregnant':
                        $norms = ['norm_pr_min', 'norm_pr_max'];
                        break;
                    case 'woman':
                        $norms = ['norm_w_min', 'norm_w_max'];
                        break;
                }
                $norm_sex_min = str_replace(',', '.', $normsModel->{$norms[0]});
                $norm_sex_max = str_replace(',', '.', $normsModel->{$norms[1]});
                $norm_min = $normsModel->norm_min;
                $norm_max = $normsModel->norm_max;                                
                
                $resultMax = [ 'actions'=>['save'=>!$user->isGuest, 'print'=>true], 'content'=>$contentStart . $model->interp_up . $linkConsult, 'success'=>true, 'type'=>'red' ];
                $resultMin = [ 'actions'=>['save'=>!$user->isGuest, 'print'=>true], 'content'=>$contentStart . $model->interp_down . $linkConsult, 'success'=>true, 'type'=>'red' ];
                $resultDoubt = [ 'actions'=>null, 'content'=>$contentStart . Html::tag('p', 'Ваш результат анализа отличен от диапазона нормальных значений в пределах 5%.<br> Этот результат не может свидетельствовать как о норме так и о патологии.<br> Рекомендуем наблюдать за показателем в динамике.'), 'success'=>true, 'type'=>'orange' ];
                $resultNormal = [ 'actions'=>['save'=>!$user->isGuest, 'print'=>false], 'content'=>$contentStart . Html::tag('p', 'Показатель в норме.'), 'success'=>true, 'type'=>'green' ];                
                $resultWrong = [ 'actions'=>null, 'content'=>$contentStart . Html::tag('p', 'Возможно значение введено неверно.'), 'success'=>true, 'type'=>'dark' ];
                $value = str_replace(',', '.', $value);
                
                if (!is_numeric($value)) {
                    return $resultWrong;
                }
                
                if ($value < $norm_sex_min) {
                    if ($value < $norm_min) {
                        return $resultWrong;
                    } elseif ($value >= self::getDoubtNorm('min', $norm_sex_min)) {
                        // исключение. ОАМ удельный вес и реакция pH
                        return ($model->id === 125 || $model->id === 126) ? $resultMin : $resultDoubt;
                    } else {
                        return $resultMin;
                    }
                } elseif ($value > $norm_sex_max) {
                    if ($value > $norm_max) {
                        return $resultWrong;
                    } elseif ($value <= self::getDoubtNorm('max', $norm_sex_max)) {
                        // исключение. ОАМ удельный вес и реакция pH 
                        return ($model->id === 125 || $model->id === 126) ? $resultMax : $resultDoubt;
                    } else {
                        return $resultMax;
                    }
                } else {
                    return $resultNormal;
                }               
            } elseif ($model->grade_id == self::GRADE_QUAL) {
                $contentStart = Html::tag('p', "{$model->name}: {$value}");
                $content = $contentStart . Html::tag('p', 'Расшифровка не найдена. Попробуйте позже.');
                $print = false;
                $type = 'orange';
                $normsModel = ResearchNormsQual::findOne(['index_id'=>$index_id, 'norm_value'=>$value, 'unit_id'=>$unit_id]);                
                
                if ($normsModel) {
                    $content = $contentStart . $normsModel->interp;
                    if ($normsModel->is_norm) {
                        $type = 'green';                        
                    } else {                        
                        $content = $content . ($showLinks ? $linkConsult : null);
                        $print = true;
                        $type = 'red';
                    }                    
                }
                
                return [ 'actions'=>['save'=>!$user->isGuest, 'print'=>$print], 'content'=>$content, 'type'=>$type ];
            }            
        }
        
        return [];
    }     
    
    public static function getNorms($index_id, $sex = null)
    {        
        $index = self::findOne($index_id);
        $result = [];
        
        if ($index->grade_id == self::GRADE_COL) {
            $model = ResearchNormsCol::findAll(['index_id'=>$index_id]);
            if ($model) {
                foreach ($model as $key=>$value) {
                    $result[$key]['index_id'] = $value->index_id;
                    if ($sex !== null) {
                        $result[$key]['norms'] = implode(' - ', ($sex) ? [$value->norm_m_min, $value->norm_m_max] : [$value->norm_w_min, $value->norm_w_max]);
                    } else {                        
                        $result[$key]['norms']['man'] = implode(' - ', [$value->norm_m_min, $value->norm_m_max]);
                        $result[$key]['norms']['pregnant'] = trim(implode(' - ', [$value->norm_pr_min, $value->norm_pr_max]));
                        $result[$key]['norms']['woman'] = implode(' - ', [$value->norm_w_min, $value->norm_w_max]);                        
                    }                    
                    $result[$key]['unit_id'] = $value->unit_id;
                } 
            }
        } else {
            $model = ResearchNormsQual::findAll(['index_id'=>$index_id]);
            if ($model) {
                $normModel = ResearchNormsQual::findOne(['index_id'=>$index_id, 'is_norm'=>true]);
                $norm = ($normModel) ? $normModel->norm_value : '-';
                
                foreach ($model as $key=>$value) {
                    $result[$key]['index_id'] = $value->index_id;
                    
                    if ($sex !== null) {
                        $result[$key]['norms'] = $norm;
                    } else {
                        $result[$key]['norms']['man'] = $norm;
                        $result[$key]['norms']['pregnant'] = $norm;
                        $result[$key]['norms']['woman'] = $norm;
                    }
                    $result[$key]['unit_id'] = $value->unit_id;
                }
            }
        }
        
        return $result;
    } 
}