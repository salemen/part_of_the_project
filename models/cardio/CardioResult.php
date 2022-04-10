<?php
namespace app\models\cardio;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CardioResult extends ActiveRecord
{
    public static function tableName()
    {
        return 'cardio_result';
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
            [['cardio_id', 'p_1', 'pq_1', 'qrs_1', 'qt_1', 'rr_1', 'deg_1', 'chss_1', 'eos_1', 'rythm_1', 'result'], 'required'],
            [['cardio_id', 'created_at'], 'integer'],
            [['result'], 'string'],
            [['p_1', 'pq_1', 'qrs_1', 'qt_1', 'rr_1', 'deg_1', 'chss_1', 'eos_1', 'rythm_1'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'cardio_id'=>'Заявка на расшифровку ЭКГ',
            'p_1'=>'P, мс',
            'pq_1'=>'PQ, мс',
            'qrs_1'=>'QRS, мс',
            'qt_1'=>'QT, мс',
            'rr_1'=>'RR, мс',
            'deg_1'=>'Угол &alpha;, град',
            'chss_1'=>'ЧСС, уд/мин',            
            'eos_1'=>'ЭОС',
            'rythm_1'=>'Ритм',
            'result'=>'Заключение ЭКГ',
            'created_at'=>'Дата расшифровки'
        ];
    }
    
    public function getCardio()
    {
        return $this->hasOne(Cardio::className(), ['id'=>'cardio_id']);
    }        
}