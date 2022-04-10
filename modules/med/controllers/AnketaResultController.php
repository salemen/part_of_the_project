<?php
namespace app\modules\med\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\anketa\Anketa;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaSession;
use app\models\anketa\search\AnketaSession as AnketaSessionSearch;
use app\models\employee\Employee;
use app\models\patient\Patient;

class AnketaResultController extends Controller
{        
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                         
                        'matchCallback'=>function() {
                            return Yii::$app->session->has('employee_santal');
                        }
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex($anketa_id)
    {
        $searchModel = new AnketaSessionSearch();
        $dataProvider = $searchModel->searchList(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['anketa_id'=>$anketa_id]);
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'anketa_id'=>$anketa_id
        ]);
    }     
    
    public function actionView($id)
    {
        $session = AnketaSession::findOne($id);
        $anketa = Anketa::findOne($session->anketa_id);
        $questions = AnketaQuestion::find()->where(['anketa_id'=>$anketa->id, 'parent_id'=>null])->orderBy('position')->all();        
        $date = $session->created_at;
        $user = (Patient::findOne($session->patient_id)) ? : Employee::findOne($session->patient_id);
        $max_answer_count = 0;
        
        if ($questions) {
            foreach ($questions as $question) {
                $count = count($question->anketaAnswers);
                if ($count > $max_answer_count) { $max_answer_count = $count; }
            }
        }

        return $this->render('view', [
            'anketa'=>$anketa,
            'date'=>$date,
            'session_id'=>$session->id,
            'user'=>$user,  
            'questions'=>$questions,
            'max_answer_count'=>$max_answer_count
        ]);
    }
}