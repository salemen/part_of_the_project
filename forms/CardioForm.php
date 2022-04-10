<?php
// Форма заявки на расшифровку ЭКГ

namespace app\forms;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;
use app\models\cardio\Cardio;
use app\models\cardio\CardioDocs;

class CardioForm extends Model
{
    // personal data
    public $user_f;
    public $user_i;
    public $user_o;
    public $user_birth;
    public $sex;
    public $email;
    public $phone;    
    
    // cardio data
    public $ekg_current;
    public $ekg_prev;
    public $ekg_date;
    public $patient_height;
    public $patient_weight;
    public $patient_sicks;
    public $patient_drugs;
    public $patient_target;
    
    public function init()
    {
        parent::init();

        $user = Yii::$app->user;
        
        if (!$user->isGuest) {
            $user = $user->identity;
            $fullname = explode(' ', $user->fullname);
            $this->user_f = $fullname[0];
            $this->user_i = $fullname[1];
            $this->user_o = isset($fullname[2]) ? $fullname[2] : '-';
            $this->user_birth = $user->user_birth;
            $this->sex = $user->sex;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_o', 'user_birth', 'sex', 'email', 'phone', 'ekg_date', 'ekg_current', 'patient_height', 'patient_weight', 'patient_sicks', 'patient_drugs', 'patient_target'], 'required'],
            [['user_f', 'user_i', 'user_o', 'user_birth', 'email', 'phone', 'ekg_date', 'patient_height', 'patient_weight'], 'string', 'max'=>255],
            [['user_f', 'user_i', 'user_o', 'email'], 'filter', 'filter'=>'trim', 'skipOnArray'=>true],
            [['patient_sicks', 'patient_drugs', 'patient_target'], 'string'],
            [['sex'], 'integer'],
            [['email'], 'email'],
            [['phone'], PhoneInputValidator::className()],
            [['user_birth', 'ekg_date'], 'match', 'pattern'=>'/\d{2}.\d{2}.\d{4}/'],
            [['ekg_current', 'ekg_prev'], 'file', 'maxFiles'=>5, 'extensions'=>'jpg, jpeg, png']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_birth'=>'Дата рождения',
            'email'=>'E-mail',
            'phone'=>'Номер телефона',
            'sex'=>'Пол',
            'ekg_current'=>'Выберите снимки ЭКГ',
            'ekg_prev'=>'Выберите снимки предыдущих ЭКГ (необязательно)',
            'ekg_date'=>'Дата снятия ЭКГ',
            'patient_sicks'=>'Жалобы / Наличие заболеваний',
            'patient_drugs'=>'Принимаемые лекарственные препараты',
            'patient_target'=>'Цель регистрации ЭКГ',
        ];
    }

    public function save()
    {
        if (!$this->validate()) { return null; }
        
        $user = $this->getUser(); 
        
        if ($user !== null) {            
            $transaction = Yii::$app->db->beginTransaction();
            $model = new Cardio();
            $model->patient_id = $user->id;
            $model->ekg_date = $this->ekg_date;
            $model->patient_height = $this->patient_height;
            $model->patient_weight = $this->patient_weight;
            $model->patient_sicks = $this->patient_sicks; 
            $model->patient_drugs = $this->patient_drugs;       
            $model->patient_target = $this->patient_target; 
            
            if ($model->save() && $this->uploadImages($model)) {
                $transaction->commit();
                return $model;
            } else {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Не удалось сохранить заявку.');
            }
        }
        
        return null;
    }
    
    protected function getUser()
    {
        $user = Yii::$app->user;
        
        if (!$user->isGuest) {
            return $user->identity;
        }
        
        throw new UnauthorizedHttpException('Вам запрещено выполнять данное действие. Пожалуйста авторизуйтесь.');
    } 
    
    protected function uploadImages($model)
    {
        $files = [
            ['attribute'=>'ekg_current', 'type'=>CardioDocs::TYPE_CURRENT],
            ['attribute'=>'ekg_prev', 'type'=>CardioDocs::TYPE_PREVIOUS]
        ];
        
        foreach ($files as $file) {
            $attribute = $file['attribute'];
            $type = $file['type'];
            
            $uploads = UploadedFile::getInstances($this, $attribute);
        
            if ($uploads) {
                foreach ($uploads as $upload) {
                    $filename = uniqid() . '.' . $upload->getExtension();
                    $upload->saveAs('uploads/' . $filename);
                    
                    $owner = new CardioDocs();       
                    $owner->cardio_id = $model->id;
                    $owner->file = $filename;
                    $owner->type = $type;

                    if (!$owner->save()) {
                        throw new ServerErrorHttpException('Не удалось загрузить изображение.');
                    }
                }
            }
        }
        
        return true;
    }
}