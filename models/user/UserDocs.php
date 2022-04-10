<?php
namespace app\models\user;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class UserDocs extends ActiveRecord
{    
    public $file;
    
    public static function tableName()
    {
        return 'user_docs';
    }
    
    public function behaviors()
    {
        return [
            'blameable'=>[
                'class'=>BlameableBehavior::className(),
                'createdByAttribute'=>'user_id',
                'updatedByAttribute'=>false
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['doc_name', 'doc_file', 'doc_ext', 'user_id'], 'string', 'max'=>255],
            [['file'], 'file', 'maxSize'=>15728640, 'tooBig'=>'Размер загружаемого документа не может превышать 15 Мб'],
            [['file'], 'checkExtension']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'ID Пользователя',
            'doc_name'=>'Название документа',
            'doc_file'=>'Документ',
            'doc_ext'=>'Формат документа',
            'created_at'=>'Дата'                        
        ];
    }
    
    public function checkExtension($attribute)
    {
        $ext = pathinfo($this->file->name)['extension'];
        
        if (in_array($ext, ['bat', 'cmd', 'css', 'dll', 'exe', 'htm', 'html', 'jar', 'jav', 'java', 'js', 'jse', 'php', 'pl', 'xml'])) {
            $this->addError($attribute, 'Такой формат файлов запрещен.');
        }
    }
    
    public static function getDocType($id)
    {
        $type = self::findOne($id)->doc_ext;
        
        $excel = ['xls', 'xlsx'];
        $image = ['bmp', 'jpg', 'jpeg', 'png'];
        $pdf = ['pdf'];
        $text = ['txt'];
        $word = ['doc', 'docx', 'rtf'];
        
        if (in_array($type, $excel)) {
            return 'excel';
        } elseif (in_array($type, $image)) {
            return 'image';
        } elseif (in_array($type, $pdf)) {
            return 'pdf';
        } elseif (in_array($type, $text)) {
            return 'text';
        } elseif (in_array($type, $word)) {
            return 'word';
        }
    }
    
    public static function getDocsCount($user_id)
    {
        return self::find()->where(['user_id'=>$user_id])->count();
    }
}