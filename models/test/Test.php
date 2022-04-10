<?php
namespace app\models\test;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\test\TestGroup;

class Test extends ActiveRecord
{ 
    const ZUNG_TEST_ID = 1;
    
    public $emails;
    
    public static function tableName()
    {
        return 'test';
    }
    
    public function afterFind()
    {
        $this->emails = ArrayHelper::getColumn($this->testEmails, 'email');
        
        parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        TestEmails::deleteAll(['test_id'=>$this->id]);
        
        if ($this->emails) {
            foreach ($this->emails as $email) {
                (new TestEmails(['test_id'=>$this->id, 'email'=>$email]))->save();
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() 
    {
        $relations = [
            $this->testEmails,
            $this->testGroups
        ];
   
        foreach ($relations as $relation) {
            if ($relation) {
                foreach ($relation as $el) {
                    $el->delete();
                }
            }
        }
        
        return parent::beforeDelete();
    }
    
    public function behaviors()
    {
        return [
            'uploadBehavior'=>[
                'class'=>UploadBehavior::className(),
                'attributes'=>[
                    'img'=>[
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
            [['name'], 'required'],
            [['desc'], 'string'],
            [['name', 'img'], 'string', 'max'=>255],
            [['emails'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Название',
            'desc'=>'Описание',
            'img'=>'Изображение',
            'emails'=>'Почтовые адреса для рассылки оповещений о прохождении теста (необязательно)'
        ];
    } 
    
    public function getTestEmails()
    {
        return $this->hasMany(TestEmails::className(), ['test_id'=>'id']);
    }
    
    public function getTestGroups()
    {
        return $this->hasMany(TestGroup::className(), ['test_id'=>'id']);
    }
}