<?php
// Форма копирования норм из одного показателя в другой
// Внимание! Все имеющиеся нормы показателя, в который копируются данные, будут удалены

namespace app\modules\admin\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use app\models\research\ResearchIndex;
use app\models\research\ResearchNormsCol;
use app\models\research\ResearchNormsQual;

class ResearchNormsCopyForm extends Model
{    
    public $copy_from;
    public $copy_to;
    public $type_id;
    
    private $norms;

    public function rules()
    {
        return [
            [['copy_from', 'copy_to'], 'required'],
            [['copy_from', 'copy_to', 'type_id'], 'integer'],
            [['type_id'], 'validateAttributes']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'copy_from'=>'Копируемый показатель',
            'copy_to'=>'Целевой показатель',
            'type_id'=>'Вид исследования'
        ];
    }
    
    public function save()
    {
        if ($this->validate()) {
            $norms = $this->norms;
            $transaction = Yii::$app->db->beginTransaction();
            
            $this->deleteValues();
            
            foreach ($norms as $norm) {
                $className = $norm::className();
                $attributes = $norm->attributes;
                unset($attributes['id'], $attributes['index_id'], $attributes['created_at']);
                
                $model = new $className(['index_id'=>$this->copy_to]);
                $model->setAttributes($attributes);
                if (!$model->save()) {
                    $transaction->rollBack();
                    $this->addError('type_id', 'Ошибка при сохранении данных.');
                    return false;
                }                
            }
            
            $transaction->commit();
            return true;
        } else {
            return false;
        }
    }
    
    public function validateAttributes($attribute)
    {
        $from = $this->findModel($this->copy_from);
        $to = $this->findModel($this->copy_to);
        
        if ($this->copy_from === $this->copy_to) {
            $this->addError($attribute, 'Показатели должны быть разными.');
        }
        
        if ($from->grade_id !== $to->grade_id) {
            $this->addError($attribute, 'Копирование показателей с разным сравнительным методом запрещено.');
        }
        
        $model = $this->findModel($this->copy_from);
        if ($model->grade_id === ResearchIndex::GRADE_COL) {
            $this->norms = ResearchNormsCol::findAll(['index_id'=>$this->copy_from]);
        } elseif ($model->grade_id === ResearchIndex::GRADE_QUAL) {
            $this->norms = ResearchNormsQual::findAll(['index_id'=>$this->copy_from]);
        } else {
            return false;
        }
        
        if (!$this->norms) {
            $this->addError('copy_from', 'У выбранного показателя не найдены нормы.');
        }
    }
    
    protected function deleteValues()
    {
        $model = $this->findModel($this->copy_to);
        
        if ($model->grade_id === ResearchIndex::GRADE_COL) {
            ResearchNormsCol::deleteAll(['index_id'=>$this->copy_to]);
        } elseif ($model->grade_id === ResearchIndex::GRADE_QUAL) {
            ResearchNormsQual::deleteAll(['index_id'=>$this->copy_to]);
        } else {
            return;
        }
    }

    protected function findModel($id)
    {
        if (($model = ResearchIndex::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}