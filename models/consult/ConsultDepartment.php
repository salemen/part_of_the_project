<?php

namespace app\models\consult;

use app\modules\b2b\models\ConsultType;
use Yii;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\payments\Payments;
use app\models\data\Department;

class ConsultDepartment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'consult';
    }


    public function rules()
    {
        return [
            [['employee_id', 'patient_id'], 'required'],
            [['dep_id', 'e_hide', 'p_hide', 'is_canceled', 'is_end', 'is_payd', 'is_special', 'created_at', 'ended_at'], 'integer'],
            [['employee_id', 'patient_id', 'comment'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'employee_id'=>'Сотрудник',
            'patient_id'=>'Пациент',
            'comment'=>'Комментарий',
            'dep_id'=>'Подразделение',
            'e_hide'=>'Скрыта у врача',
            'p_hide'=>'Скрыта у пациента',
            'is_canceled'=>'Отменена',
            'is_end'=>'Завершена',
            'is_payd'=>'Оплачена',
            'is_special'=>'Онлайн консультация по COVID19',
            'created_at'=>'Дата начала',
            'ended_at'=>'Дата окончания'
        ];
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id'=>'dep_id']);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }

    public function getEmployeePatient()
    {
        return $this->hasOne(Employee::className(), ['id'=>'patient_id']);
    }

    public function getHistory()
    {
        return $this->hasMany(ConsultHistory::className(), ['consult_id'=>'id'])
            ->orderBy(['consult_id' => SORT_DESC]);;
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'patient_id']);
    }

    public function getCardios()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }

    public function getContype()
    {
        return $this->hasMany(ConsultType::className(), ['consult'=>'is_special']);
    }

    public function getPayment()
    {
        return $this->hasOne(Payments::className(), ['orderNumber'=>'id'])
            ->andWhere(['orderType'=>Payments::TYPE_CONSULT]);
    }

    public static function isConsultExist($employee_id, $patient_id, $is_special = false)
    {
        if (self::findOne(['employee_id'=>$employee_id, 'patient_id'=>$patient_id, 'is_payd'=>false, 'is_special'=>$is_special])) {
            return true;
        } elseif (self::findOne(['employee_id'=>$employee_id, 'patient_id'=>$patient_id, 'is_payd'=>true, 'is_end'=>false, 'is_special'=>$is_special])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isConsultAllowed($id)
    {
        $user = Yii::$app->user;

        return ($user->isGuest)? false : self::find()
            ->where(['id'=>$id, 'is_canceled'=>false, 'is_payd'=>true])
            ->andWhere(['OR', ['employee_id'=>$user->id], ['patient_id'=>$user->id]])
            ->exists();
    }

    public static function isConsultNotPayd($id)
    {
        return self::find()->where(['id'=>$id, 'is_payd'=>false])->exists();
    }

    public static function isConsultSecond($employee_id)
    {
        $user = Yii::$app->user;

        return ($user->isGuest) ? false : self::find()->where(['employee_id'=>$employee_id, 'patient_id'=>$user->id, 'is_payd'=>true, 'is_end'=>true])->exists();
    }

    public static function isDepNotExists($id)
    {
        return self::find()->where(['id'=>$id, 'is_end'=>false, 'dep_id'=>null])->exists();
    }

    public static function isEmployeeConsultNotEnd()
    {
        return self::find()->where(['employee_id'=>Yii::$app->user->id, 'is_end'=>false])->exists();
    }

    public static function isPatientConsultNotPayd()
    {
        return self::find()->where(['patient_id'=>Yii::$app->user->id, 'is_payd'=>false])->exists();
    }

    public static function getConsults($one = false)
    {
        $user = Yii::$app->user;

        if ($user->identity instanceof Employee) {
            $condition = ['AND',
                ['OR', ['employee_id'=>$user->id, 'is_payd'=>true, 'e_hide'=>false], ['patient_id'=>$user->id, 'p_hide'=>false]],
                ['is_canceled'=>false]
            ];
        } else {
            $condition = ['patient_id'=>$user->id, 'p_hide'=>false, 'is_canceled'=>false];
        }

        $model = self::find()
            ->joinWith(['history'])
            ->where($condition)
            ->orderBy([
                'consult.is_end'=>SORT_ASC,
                'consult_history.created_at'=>SORT_DESC,
                'consult.created_at'=>SORT_DESC
            ]);

        return $one ? $model->one() : $model->all();
    }

    public static function getConsultCost($model)
    {
        if ($model) {
            $cost = $model->cost;
            $cost_2nd = $model->cost_2nd;
            $employee_id = $model->employee_id;
            $user = Yii::$app->user;

            if ($user->isGuest) {
                return $cost;
            } else {
                $isExists = self::find()->where(['employee_id'=>$employee_id, 'patient_id'=>$user->id, 'is_payd'=>true, 'is_end'=>true])->exists();
                if ($isExists) {
                    return ($cost_2nd === null) ? $cost : $cost_2nd;
                } else {
                    return $cost;
                }
            }
        }

        return false;
    }

    public static function getConsultsCount($user_id)
    {
        $condition = (Yii::$app->session->has('employee_santal')) ? ['employee_id'=>$user_id] : ['patient_id'=>$user_id];

        return self::find()->joinWith('payment')->where($condition)->andWhere(['is_canceled'=>false, 'is_payd'=>true, 'isTest'=>false])->count();
    }

    public static function getConsultCountByParams($employee_id = null, $dep_id = null, $period = null)
    {
        $query = self::find()->joinWith('payment')->where(['orderType'=>10, 'is_canceled'=>false, 'is_payd'=>true, 'isTest'=>false]);

        if ($employee_id !== null) {
            $query->andWhere(['employee_id'=>$employee_id]);
        }

        if ($dep_id !== null) {
            $query->andWhere(['dep_id'=>$dep_id]);
        }

        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }

            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }

        $result = $query->count();

        return (int)$result;
    }

    public static function getConsultSumByParams($employee_id = null, $dep_id = null, $period = null)
    {
        $query = self::find()->select('SUM(shopSumAmount)')->joinWith('payment')->where(['orderType'=>10, 'is_canceled'=>false, 'is_payd'=>true, 'isTest'=>false]);

        if ($employee_id !== null) {
            $query->andWhere(['employee_id'=>$employee_id]);
        }

        if ($dep_id !== null) {
            $query->andWhere(['dep_id'=>$dep_id]);
        }

        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }

            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }

        $result = $query->scalar();

        return (float)$result;
    }
}
