<?php
// Шифрование сообщений в чате (строка-дешифратор хранится в params-local - cryptKey)

namespace app\helpers;

use Yii;
use yii\web\ServerErrorHttpException;

class CryptHelper
{
    protected $key;
    protected $method;    
    
    public function __construct($key, $method = 'AES-256-CBC')
    {
        $this->key = $key;
        $this->method = $method;
    }
    
    public function encrypt($value)
    {
        $iv = Yii::$app->security->generateRandomString(16);       
        $json = json_encode([
            'iv'=>base64_encode($iv),
            'value'=>openssl_encrypt($value, $this->method, $this->key, 0, $iv)
        ]);
 
        return base64_encode($json);
    }
    
    public function decrypt($value)
    {
        $data = json_decode(base64_decode($value), true);
        
        if (!$this->validOptions($data)) {
            throw new ServerErrorHttpException('Передана некорректная строка для расшифровки.');
        }
 
        $iv = base64_decode($data['iv']);
        
        return openssl_decrypt($data['value'], $this->method, $this->key, 0, $iv);
    }
    
    protected function validOptions($options)
    {
        if (!is_array($options)) {
            return false;
        }
        
        return isset($options['iv'], $options['value']);
    }
}