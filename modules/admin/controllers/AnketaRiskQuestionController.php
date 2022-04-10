<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaRiskQuestion;
use app\models\anketa\search\AnketaRiskQuestion as AnketaRiskQuestionSearch;

class AnketaRiskQuestionController extends Controller
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
                        'roles'=>['admin']
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'delete'=>['POST']
                ]
            ]
        ];
    }
    
    public function actionIndex($group_id)
    {
        $searchModel = new AnketaRiskQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $group_id);
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'group_id'=>$group_id
        ]);
    }
    
    public function actionCreate($group_id)
    {
        $model = new AnketaRiskQuestion();
        $model->group_id = $group_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'group_id'=>$model->group_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model,
            'group_id'=>$group_id
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'group_id'=>$model->group_id]);
        }

        return $this->render('_form', [
            'model'=>$model,
            'group_id'=>$model->group_id
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $group_id = $model->group_id;
        $model->delete();
        
        return $this->redirect(['index', 'group_id'=>$group_id]);
    }
    
    public function actionQuestionAnswers()
    {
        Yii::$app->response->format = 'json';
        $question_id = Yii::$app->request->post('question_id');
        $answers = AnketaAnswer::find()->where(['question_id'=>$question_id])->all();
        $data = [['id'=>'', 'text'=>'']];
        
        foreach ($answers as $answer) {
            $data[] = ['id'=>$answer->id, 'text'=>$answer->name];
        }
        
        return ['data'=>$data];
    }
    
    protected function findModel($id)
    {
        if (($model = AnketaRiskQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена');
    }
}

