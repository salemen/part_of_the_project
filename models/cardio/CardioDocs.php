<?php
namespace app\models\cardio;

use Yii;
use yii\db\ActiveRecord;

class CardioDocs extends ActiveRecord
{
    const TYPE_CURRENT = 10;
    const TYPE_PREVIOUS = 20;
    
    public static function tableName()
    {
        return 'cardio_docs';
    }

    public function rules()
    {
        return [
            [['cardio_id', 'file', 'type'], 'required'],
            [['cardio_id', 'type'], 'integer'],
            [['file'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'cardio_id'=>'ID Заявки',
            'file'=>'Файл',
            'type'=>'Тип'
        ];
    }
}
