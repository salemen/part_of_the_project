<?php
namespace app\modules\b2b\forms;

use Yii;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use app\helpers\AppHelper;
use app\models\data\Department;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;
use app\models\employee\EmployeeRoles;

class EmployeeImportForm extends Model
{
    public $file;
    public $org_id;

    private $columnsCount = 7;

    public function attributeLabels()
    {
        return [
            'file' => 'Файл',
            'org_id' => 'Организация, в которой трудоустроены сотрудники'
        ];
    }

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'file' => [
                        'path' => '@storage/temp',
                        'tempPath' => '@storage/temp',
                        'url' => false
                    ]
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            [['file', 'org_id'], 'required'],
            [['file', 'org_id'], 'string']
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $file = "storage/temp/{$this->file}";
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            if ($rows) {
                $transaction = Yii::$app->db->beginTransaction();
                $userIds = [];

                foreach ($rows as $key => $row) {
                    if (!$this->validateRow($row)) {
                        $transaction->rollBack();
                        return false;
                    }

                    $user = $this->saveUser($row);

                    if ($user == null) {
                        $transaction->rollBack();
                        throw new ServerErrorHttpException('Ошибка при сохранении пользователя.');
                    }

                    if (!$this->savePosition($user, trim($row[6]))) {
                        $transaction->rollBack();
                        throw new ServerErrorHttpException('Ошибка при сохранении организации пользователя.');
                    }

                    array_push($userIds, $user->id);
                }

                $transaction->commit();
                EmployeeRoles::updateAll(['is_santal' => 0], ['IN', 'employee_id', $userIds]);
                unlink("storage/temp/{$this->file}");

                return true;
            }
        }

        return false;
    }

    protected function validateRow($row)
    {
        for ($i = 0; $i < $this->columnsCount; $i++) {
            $col = $row[$i];
            if (!isset($col) && $col == null) {
                return false;
            }
        }

        return true;
    }

    protected function saveNotification($user, $password)
    {
        $email = $user->email;
        $phone = $user->phone;

        $message = "Для Вас была создана учетная запись для доступа к сервисам ГК САНТАЛЬ-ЦСМ.<br><br>"
            . "Для авторизации в системе, пожалуйста, используйте эти данные:<br>"
            . "Номер телефона: <b>{$phone}</b><br>"
            . "Email: <b>{$email}</b><br>"
            . "Пароль: <b>{$password}</b><br>";

        $subject = base64_encode('Учетная запись для доступа к сервисам ГК САНТАЛЬ-ЦСМ');
        $message = base64_encode($message);

        Yii::$app->db_univer->createCommand("INSERT INTO `cron_notification` (`id`, `target`, `subject`, `message`, `is_sent`, `created_at`, `updated_at`) VALUES (NULL, '$email', '$subject', '$message', '0', '" . time() . "', NULL);")->execute();
        return true;
    }

    protected function savePosition($user, $empl_pos)
    {
        $model = new EmployeePosition([
            'id' => Yii::$app->security->generateRandomString(16),
            'employee_id' => $user->id,
            'empl_pos' => $empl_pos,
            'empl_dep' => $this->setDepartment(),
            'type' => 'Основное место работы',
            'org_id' => $this->org_id,
            'is_doctor' => 1,
            'is_santal' => 0
        ]);

        if ($model->save()) {
            return true;
        } else {
            die(var_dump($model->getErrors()));
        }

        return $model->save();
    }

    protected function saveUser($row)
    {
        $fullname = trim($row[0]);
        $sex = trim($row[1]);
        $user_birth = trim($row[2]);
        $city = trim($row[3]);
        $phone = AppHelper::normalizePhone(trim($row[4]));
        $email = trim($row[5]);
        $empl_pos = trim($row[6]);

        $password = sprintf("%06d", rand(1, 999999));

        $model = new Employee([
            'id' => Yii::$app->security->generateRandomString(32),
            'fullname' => $fullname,
            'username' => null,
            'user_birth' => $user_birth,
            'city' => $city,
            'email' => $email,
            'phone' => $phone,
            'sex' => ($sex == 'М') ? 1 : 0
        ]);

        if ($model->save() && $model->setAuth($model->id, $password)) {
            $this->saveNotification($model, $password);
            return $model;
        }

        return null;
    }

    protected function setDepartment()
    {
        $model = Department::findOne(['org_id' => $this->org_id]);

        return ($model) ? $model->name : 'Основное';
    }
}