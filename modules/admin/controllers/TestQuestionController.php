<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\test\TestGroup;
use app\models\test\TestQuestion;
use app\models\test\search\TestQuestion as TestQuestionSearch;

class TestQuestionController extends Controller
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
        $searchModel = new TestQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $group_id);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'group_id'=>$group_id
        ]);
    }
    
    public function actionCreate($group_id)
    {
        $count = false;
        $model = new TestQuestion();
        $model->group_id = $group_id;
        $model->test_id = $this->getTestId($group_id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'group_id'=>$model->group_id]);
        }
        
        return $this->render('_form', [
            'model'=>$model,
            'count'=>$count
        ]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $count = count($model->answers);
        $model->answers = $model->testAnswers;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->redirect(['index', 'group_id'=>$model->group_id]);
        }

        return $this->render('_form', [
            'model'=>$model,
            'count'=>$count
        ]);
    }
       
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $group_id = $model->group_id;
        $model->delete();
        
        return $this->redirect(['index', 'group_id'=>$group_id]);
    }
    
    protected function findModel($id)
    {
        if (($model = TestQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    private function getTestId($group_id)
    {
        return TestGroup::find()->select('test_id')->where(['id'=>$group_id])->scalar();
    }
}