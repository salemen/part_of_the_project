<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;
use app\models\data\Organization;

class EmployeePosition extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_position';
    }

    public function rules()
    {
        return [
            [['employee_id', 'empl_pos', 'empl_dep', 'org_id'], 'required', 'on' => 'edit'],
            [['empl_pos'], 'unique', 'targetAttribute' => ['employee_id', 'empl_pos', 'empl_dep', 'org_id'], 'message' => 'Эта должность уже указана для данного сотрудника', 'on' => 'edit'],
            [['id', 'employee_id'], 'required'],
            [['org_id', 'is_doctor', 'is_santal', 'status'], 'integer'],
            [['employee_id', 'empl_pos', 'empl_dep', 'type'], 'string', 'max' => 255],
            [['id'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id' => 'Сотрудник',
            'empl_pos' => 'Должность',
            'empl_dep' => 'Подразделение',
            'type' => 'Тип',
            'org_id' => 'Организация',
            'is_doctor' => 'Доктор',
            'is_santal' => 'Сотрудник ГК САНТАЛЬ',
            'status' => 'Статус'
        ];
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'org_id']);
    }

    public static function getOrgIds($user_id = null)
    {
        $result = [];
        $employee_id = ($user_id) ? $user_id : Yii::$app->user->id;
        $model = self::findAll(['employee_id' => $employee_id]);

        if ($model) {
            foreach ($model as $value) {
                array_push($result, $value->org_id);
            }
        }

        return $result;
    }

    //Метод получения данных по столбцу is_santal и использования при добавлении фото сотрудникам организаций в лк
    public static function getIsSantal($user_id = null)
    {
        $result = [];
        $employee_id = ($user_id) ? $user_id : Yii::$app->user->id;
        $model = self::findAll(['employee_id'=>$employee_id]);

        if ($model) {
            foreach ($model as $value) {
                array_push($result, $value->is_santal);
            }
        }

        return $result;
    }


}