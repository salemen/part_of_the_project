<?php
namespace app\models\employee;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\patient\Patient;
use app\models\user\UserData;
use app\models\monitor\MonitorPassport;
use app\models\employee\EmployeeRoles;

class Employee extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_PAUSE = 20;
    const STATUS_INACTIVE = 0;

    public $password;

    public static function tableName()
    {
        return 'employee';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ],
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'photo' => [
                        'path' => '@storage/avatar',
                        'tempPath' => 'temp',
                        'url' => false
                    ]
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            [['fullname', 'city', 'user_birth', 'sex', 'phone', 'email'], 'required', 'on' => 'edit'],
            [['id', 'guid', 'auth_key', 'username'], 'unique'],
            [['id', 'fullname'], 'required'],
            [['sex', 'status', 'created_at', 'last_activity'], 'integer'],
            [['id', 'guid', 'fullname', 'snils', 'username', 'city', 'user_birth', 'phone', 'phone_work', 'email', 'photo', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'validateEmail', 'on' => 'edit'],
            [['phone'], 'validatePhone', 'on' => 'edit'],
            [['snils'], 'validateSnils', 'on' => 'edit'],
            [['password'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'GUID',
            'fullname' => 'ФИО',
            'snils' => 'СНИЛС',
            'username' => 'Логин',
            'city' => 'Город',
            'user_birth' => 'Дата рождения',
            'phone_work' => 'Рабочий номер телефона',
            'phone' => 'Номер телефона',
            'email' => 'E-mail',
            'photo' => 'Фото',
            'auth_key' => 'Ключ авторизации',
            'sex' => 'Пол',
            'password' => 'Пароль',
            'status' => 'Статус',
            'activity' => 'Активный консультант',
            'created_at' => 'Дата регистрации',
            'last_activity' => 'Последняя активность'
        ];
    }

    public static function findByEmail($email)
    {
        return ($email) ? static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]) : null;
    }

    public static function findByPhone($phone)
    {
        return ($phone) ? static::find()->where(['OR', ['phone' => $phone], ['phone_work' => $phone]])->andWhere(['status' => self::STATUS_ACTIVE])->one() : null;
    }

    public static function findBySnils($snils)
    {
        return ($snils) ? static::findOne(['snils' => $snils, 'status' => self::STATUS_ACTIVE]) : null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findById($id)
    {
        return static::findOne($id);
    }

    public static function findByGuid($guid)
    {
        return static::findOne(['guid' => $guid, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function getAdEmail($fullname)
    {
        $ldapUser = Yii::$app->ad->search()->query("(cn=$fullname)");

        return ($ldapUser) ? $ldapUser[0]['mail'] : null;
    }

    public static function getAdUsername($fullname)
    {
        $ldapUser = Yii::$app->ad->search()->query("(cn=$fullname)");

        return ($ldapUser) ? $ldapUser[0]['samaccountname'] : null;
    }


    public function deleteAuth($user_id)
    {
        $model = EmployeeAuth::findOne(['user_id' => $user_id]);
        if ($model) {
            return $model->delete();
        } else {
            return false;
        }
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateId()
    {
        $this->id = Yii::$app->security->generateRandomString(32);
    }

    public function getAdvisor()
    {
        $advisorTable = EmployeeAdvisor::tableName();

        return $this->hasOne(EmployeeAdvisor::className(), ['employee_id' => 'id'])
            ->andWhere([$advisorTable . '.status' => EmployeeAdvisor::STATUS_ACTIVE]);
    }

    public function getAdvisors()
    {
        $advisorTable = EmployeeAdvisor::tableName();

        return $this->hasOne(EmployeeAdvisor::className(), ['employee_id' => 'id'])
            ->andWhere(['OR', [$advisorTable . '.status' => 10], [$advisorTable . '.status' => 0]]);

    }

    public function getAuth()
    {
        return $this->hasOne(EmployeeAuth::className(), ['user_id' => 'id']);
    }

    public function getPassport()
    {
        return $this->hasOne(MonitorPassport::className(), ['user_id'=>'id']);
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getBio()
    {
        $command = "SELECT bio from bio WHERE hash = '{$this->id}';";

        return Yii::$app->db_staff->createCommand($command)->queryOne();
    }

    public function getCustomPositions()
    {
        return $this->hasMany(EmployeeCustom::className(), ['employee_id' => 'id'])
            ->andOnCondition(['type' => EmployeeCustom::TYPE_POSITION]);
    }

    public function getData()
    {
        return $this->hasOne(UserData::className(), ['user_id' => 'id']);
    }

    public function getDegrees()
    {
        return $this->hasMany(EmployeeDegree::className(), ['employee_id' => 'id'])
            ->select(['employee_id', 'empl_degree'])
            ->orderBy('empl_degree')
            ->distinct('empl_degree');
    }

    public function getDocuments()
    {
        return $this->hasMany(EmployeeDocument::className(), ['employee_id' => 'id'])
            ->select(['employee_id', 'empl_qual'])
            ->orderBy('empl_qual')
            ->distinct('empl_qual');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPositions()
    {
        return $this->hasMany(EmployeePosition::className(), ['employee_id' => 'id']);
    }

    public function getPosition()
    {
        $result = $this->hasOne(EmployeePosition::className(), ['employee_id' => 'id'])
            ->andWhere(['OR', ['type' => 'Основное место работы'], ['type' => 'Внутреннее совместительство']]);

        return ($result) ? $result : $this->hasOne(EmployeePosition::className(), ['employee_id' => 'id']);
    }

    public function getPositionsActive()
    {
        return $this->hasMany(EmployeePosition::className(), ['employee_id' => 'id'])
            ->andWhere(['employee_position.status' => 10])
            ->distinct('employee_id');
    }

    public function getPositionsDoctor()
    {
        return $this->hasMany(EmployeePosition::className(), ['employee_id' => 'id'])
            ->select(['employee_id', 'empl_pos'])
            ->andWhere(['employee_position.is_doctor' => true])
            ->orderBy('empl_pos')
            ->distinct('empl_pos');
    }

    public function getPositionsDoctorAll()
    {
        return $this->hasMany(EmployeePosition::className(), ['employee_id' => 'id'])
            ->andWhere(['employee_position.is_doctor' => true])
            ->distinct('empl_pos');
    }

    public function getRanks()
    {
        return $this->hasMany(EmployeeDegree::className(), ['employee_id' => 'id'])
            ->select(['employee_id', 'empl_rank'])
            ->orderBy('empl_rank')
            ->distinct('empl_rank');
    }

    public function getRoles()
    {
        return $this->hasOne(EmployeeRoles::className(), ['employee_id' => 'id']);
    }

    public function getSex()
    {
        $arr = array();
        $arr[1] = 'мужской';
        $arr[0] = 'женский';
        return $arr[$this->sex];
    }

    public function isScheduleExists()
    {
        $command = "SELECT EXISTS (SELECT * from employees WHERE full_name = '{$this->fullname}' AND active = 1 AND role_id = 3);";

        return Yii::$app->db_schedule->createCommand($command)->queryScalar();
    }

    public function setAuth($user_id, $password)
    {
        $model = new EmployeeAuth();
        $model->setUserId($user_id);
        $model->setPassword($password);

        if ($model->save()) {
            return true;
        } else {
            self::findOne($user_id)->delete();
            return false;
        }
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $exists = false;

            if ($this->email == '') {
                $this->addError($attribute, 'E-mail адрес не может быть пустым.');
                return false;
            }

            if ($exists === false) {
                $exists = self::find()->where(['email' => $this->email])->andWhere(['!=', 'id', $this->id])->exists();
            }

            if ($exists === false) {
                $exists = Patient::find()->where(['email' => $this->email])->andWhere(['!=', 'id', $this->id])->exists();
            }

            if ($exists) {
                $this->addError($attribute, 'Пользователь с таким E-mail уже зарегистрирован.');
                return false;
            } else {
                return true;
            }
        }
    }

    public function validatePassword($password)
    {
        
        if (!$this->roles->is_santal || !$this->roles->is_official) {
            return Yii::$app->security->validatePassword($password, $this->auth->password_hash);
        } else {
            return constant('YII_DEBUG') ? true : Yii::$app->ad->authenticate($this->username, $password);
        }
    }

    public function validatePhone($attribute)
    {
        if (!$this->hasErrors()) {
            $exists = false;

            if ($this->phone == '') {
                $this->addError($attribute, 'Телефон не может быть пустым.');
                return false;
            }

            if ($exists === false) {
                $exists = self::find()->where(['phone' => $this->phone])->andWhere(['!=', 'id', $this->id])->exists();
            }

            if ($exists === false) {
                $exists = Patient::find()->where(['phone' => $this->phone])->andWhere(['!=', 'id', $this->id])->exists();
            }

            if ($exists) {
                $this->addError($attribute, 'Пользователь с таким номером телефона уже зарегистрирован.');
                return false;
            } else {
                return true;
            }
        }
    }

    public function validateSnils($attribute)
    {
        if ($this->snils) {
            if (!$this->hasErrors()) {
                $exists = false;

                if ($exists === false) {
                    $exists = self::find()->where(['snils' => $this->snils])->andWhere(['!=', 'id', $this->id])->exists();
                }

                if ($exists === false) {
                    $exists = Patient::find()->where(['snils' => $this->snils])->andWhere(['!=', 'id', $this->id])->exists();
                }

                if ($exists) {
                    $this->addError($attribute, 'Пользователь с таким СНИЛС уже зарегистрирован.');
                }
            }
        }
    }

    //проверка заполнен ли профиль
    public function isFillProfile()
    {
        $ok = true;
        $employee_payment = EmployeePayment::findOne(['employee_id' => $this->id]);
        if (!isset($employee_payment->inn)) {
            $ok = false;
        }

        $employee_degree = EmployeeDegree::findOne(['employee_id' => $this->id]);
        if (!$employee_degree) {
            $ok = false;
        }
        $employee_document = EmployeeDocument::findOne(['employee_id' => $this->id]);
        if (!$employee_document) {
            $ok = false;
        }
        $employee_category = EmployeeCategory::findOne(['employee_id' => $this->id]);
        if (!$employee_category) {
            $ok = false;
        }
        $employee_consult = EmployeeConsult::findOne(['employee_id' => $this->id]);
        if (!$employee_consult) {
            $ok = false;
        }
        return $ok;
    }

    //аватар доктора
    public static function getProfilePhoto($model)
    {
        $employee_id = $model->id;
        $first = 0;
        $models = EmployeePosition::findAll(['employee_id' => $employee_id, 'is_santal' => 1, 'status' => Employee::STATUS_ACTIVE]);
        foreach ($models as $model2) {
            $first =1;
        }
        return (($model->photo) ? ($first == '0') ? '/storage/avatar/' . $model->photo . '' : 'https://проврачей.рф/pics/photos/' . $model->photo : (($model->sex) ? '/img/noavatar-male.png' : '/img/noavatar-female.png'));
    }


    public function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}