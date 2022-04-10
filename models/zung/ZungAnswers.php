<?php
namespace app\models\zung;

use Yii;
use yii\db\ActiveRecord;

class ZungAnswers extends ActiveRecord
{
    public static function tableName()
    {
        return 'answers';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_zung');
    }

    public function rules()
    {
        return [
            [['login', 'date_time', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20', 'result'], 'required'],
            [['date_time'], 'safe'],
            [['login'], 'string', 'max'=>100],
            [['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20', 'result'], 'string', 'max'=>10]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'login'=>'Login',
            'date_time'=>'Дата заполнения',
            'q1'=>'Q1',
            'q2'=>'Q2',
            'q3'=>'Q3',
            'q4'=>'Q4',
            'q5'=>'Q5',
            'q6'=>'Q6',
            'q7'=>'Q7',
            'q8'=>'Q8',
            'q9'=>'Q9',
            'q10'=>'Q10',
            'q11'=>'Q11',
            'q12'=>'Q12',
            'q13'=>'Q13',
            'q14'=>'Q14',
            'q15'=>'Q15',
            'q16'=>'Q16',
            'q17'=>'Q17',
            'q18'=>'Q18',
            'q19'=>'Q19',
            'q20'=>'Q20',
            'result'=>'Результат'
        ];
    }
}