<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\anketa\Anketa;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\AnketaRiskCategory;
use app\models\anketa\AnketaSession;

class AnketaController extends Controller
{    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['*'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>AnketaSession::find()->where(['patient_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]); 
    }    
    
    public function actionView($id)
    {
        $session = AnketaSession::findOne($id);
        $anketa = Anketa::findOne($session->anketa_id);
        $questions = AnketaQuestion::find()->where(['anketa_id'=>$anketa->id, 'parent_id'=>null])->orderBy('position')->all();        
        $date = $session->created_at;
        $user = Yii::$app->user->identity;
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
    
    public function actionViewRisk($id)
    {
        $anketa_id = AnketaSession::findOne($id)->anketa_id;
        $categories = AnketaRiskCategory::findAll(['anketa_id'=>$anketa_id]);
        
        return $this->render('view-risk', [
            'categories'=>$categories,
            'session_id'=>$id,
            'anketa_id'=>$anketa_id
        ]);
    }
}