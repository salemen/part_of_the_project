<?php
namespace app\modules\b2b\forms;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;

class OrgSignupForm extends Model
{
    public $name;
    public $city;
    public $inn;
    public $kpp;
    public $ogrn;
    public $address;
    
    public function attributeLabels()
    {
        return [            
            'name'=>'Название организации',
            'city'=>'Город',
            'inn'=>'ИНН',
            'kpp'=>'КПП',
            'ogrn'=>'ОГРН',
            'address'=>'Юр. Адрес'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'city', 'inn', 'kpp', 'ogrn', 'address'], 'required'],
            [['name', 'city', 'inn', 'kpp', 'ogrn', 'address'], 'string'],
            [['inn'], 'unique', 'targetClass'=>Organization::className(), 'targetAttribute'=>['inn'=>'inn'], 'message'=>'Организация с таким ИНН уже существует.'],
            [['inn'], 'match', 'pattern'=>'/^\d{10}$/', 'message'=>'Значение «ИНН» должно содержать 10 цифр.'],              
            [['kpp'], 'match', 'pattern'=>'/^\d{9}$/', 'message'=>'Значение «КПП» должно содержать 9 цифр.']
        ];
    }
    
    private function saveDep($org)
    {
        $model = new Department([
            'name'=>'Основное',
            'address'=>$org->address,
            'short_address'=>$org->address,
            'alias'=>null,
            'org_id'=>$org->id,
            'is_santal'=>0
        ]);
        
        return $model->save();
    }

    private function saveOrg()
    {
        $model = new Organization([
            'name'=>$this->name,
            'city'=>$this->city,
            'inn'=>$this->inn,
            'kpp'=>$this->kpp,
            'ogrn'=>$this->ogrn,
            'address'=>$this->address,
            'is_santal'=>0
        ]);
        
        return $model->save() ? $model : null;
    } 
    
    private function savePosition($org, $user)
    {
        $model = new EmployeePosition([
            'id'=>Yii::$app->security->generateRandomString(16),
            'employee_id'=>$user->id,
            'empl_pos'=>'Представитель организации',            
            'empl_dep'=>'Основное',
            'type'=>'Основное место работы',
            'org_id'=>$org->id,
            'is_doctor'=>0,
            'is_santal'=>0
        ]);
        
        return $model->save();
    }
    
    public function signup()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $org = $this->saveOrg();
            if ($org == null) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при сохранении организации.');
            }
            
            if (!$this->saveDep($org)) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при сохранении подразделения.');
            }
            
            $user = Yii::$app->user;
            
            if (!$this->savePosition($org, $user)) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении организации пользователя.');              
            }
            
            $transaction->commit();

            return true;
        }
        
        return false;
    }
}