<?php
namespace app\models\anketa;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\db\ActiveRecord;
use app\models\anketa\AnketaPermission;

class Anketa extends ActiveRecord
{
    public $delete_file;
    
    public static function tableName()
    {
        return 'anketa';
    }
    
    public function behaviors()
    {
        return [
            'uploadBehavior'=>[
                'class'=>UploadBehavior::className(),
                'attributes'=>[
                    'file'=>[
                        'path'=>'@webroot/uploads',
                        'tempPath'=>'temp',
                        'url'=>false
                    ]
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            [['delete_file'], 'boolean'],
            [['name', 'desc'], 'required'],
            [['desc'], 'string'],
            [['status'], 'integer'],
            [['name', 'file'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Название',
            'desc'=>'Описание',
            'file'=>'Файл анкеты',
            'status'=>'Статус',
            'delete_file'=>'Удалить файл'
        ];
    }
      
    public function beforeDelete() 
    {
        $questions = $this->questions;
        $perms = $this->anketaPermissions;
        $risks = $this->anketaRiskCategories;
        
        foreach ($questions as $question) {
            $question->delete();
        }
        
        foreach ($perms as $perm) {
            $perm->delete();
        }
        
        foreach ($risks as $risk) {
            $risk->delete();
        }
        
        return parent::beforeDelete();
    }
    
    public function getAnketaPermissions()
    {
        return $this->hasMany(AnketaPermission::className(), ['anketa_id'=>'id']);
    }
    
    public function getQuestions()
    {
        return $this->hasMany(AnketaQuestion::className(), ['anketa_id'=>'id']);
    }
    
    public function getAnketaRiskCategories()
    {
        return $this->hasMany(AnketaRiskCategory::className(), ['anketa_id'=>'id']);
    }
}