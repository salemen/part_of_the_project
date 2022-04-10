<?php
// Аплоад файлов в режиме консультации
// TODO Перенести в модуль consult

namespace app\forms;

use yii\base\Model;

class UploadForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'maxSize'=>15728640, 'tooBig'=>'Размер загружаемого документа не может превышать 15 Мб'],
            [['file'], 'checkExtension']
        ];
    }
    
    public function checkExtension($attribute)
    {
        $ext = pathinfo($this->file->name)['extension'];
        
        if (in_array($ext, ['bat', 'cmd', 'css', 'dll', 'exe', 'htm', 'html', 'jar', 'jav', 'java', 'js', 'jse', 'php', 'pl', 'xml'])) {
            $this->addError($attribute, 'Такой формат файлов запрещен.');
        }
    }
}