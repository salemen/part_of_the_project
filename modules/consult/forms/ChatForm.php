<?php
namespace app\modules\consult\forms;

use yii\base\Model;

class ChatForm extends Model
{
    public $file;
    public $message;

    public function rules()
    {
        return [
            [['message'], 'string'],
            [['file'], 'file', 'maxSize'=>15728640, 'tooBig'=>'Размер загружаемого документа не может превышать 15 Мб'],
            [['file'], 'checkExtension']
        ];
    }
    
    public function checkExtension($attribute)
    {
        $ext = pathinfo($this->file->name)['extension'];
        
        if (in_array($ext, ['bat', 'cmd', 'css', 'dll', 'exe', 'htm', 'html', 'jar', 'jav', 'java', 'js', 'jse', 'php', 'pl', 'xml'])) {
            $this->addError($attribute, 'Такой формат файлов запрещен для отправки.');
        }
    }
}