<?php
namespace app\models\employee;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;

class EmployeeAdvisor extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    
    public static function tableName()
    {
        return 'employee_advisor';
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['position', 'cost', 'cost_2nd', 'status', 'created_at'], 'integer'],
            [['is_special'], 'number'],
            [['employee_id', 'seo_title', 'seo_desc'], 'string', 'max'=>255],
            [['employee_id'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id'=>'Сотрудник',
            'cost'=>'Стоимость услуги первичная (руб.)',
            'cost_2nd'=>'Стоимость услуги вторичная (руб.)',
            'is_special'=>'Консультация по COVID',
            'seo_title'=>'SEO Заголовок',
            'seo_desc'=>'SEO Описание',            
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }   
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }       
    
    public function getUrl()
    {
        return Yii::$app->urlManager->createUrl(['/doctor/view', 'id'=>$this->employee_id]);
    }       
}