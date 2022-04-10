<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaQuestion;
use app\models\anketa\search\AnketaQuestion as AnketaQuestionSearch;

class AnketaQuestionController extends Controller
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
    
    public function actionIndex($anketa_id)
    {
        $searchModel = new AnketaQuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $anketa_id);
        $types = AnketaQuestion::getTypes();
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'anketa_id'=>$anketa_id,
            'types'=>$types,
        ]);
    }
    
    public function actionCreate($type_id, $anketa_id)
    {
        $count = false;
        $model = new AnketaQuestion([
            'anketa_id'=>$anketa_id,
            'type'=>$type_id            
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->redirect(['index','anketa_id'=>$model->anketa_id]);
        }

        return $this->render('_form', [
            'count'=>$count,
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {
        $count = false;
        $model = $this->findModel($id);        
        
        if ($model->type == AnketaQuestion::TYPE_MULTI || $model->type == AnketaQuestion::TYPE_ONE) {
            $count = count($model->answers);
            $model->answers = $model->anketaAnswers;            
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
        }

        return $this->render('_form', [
            'count'=>$count,
            'model'=>$model
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        
        return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
    }
    
    public function actionSort($anketa_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>AnketaQuestion::find()->where(['anketa_id'=>$anketa_id])->orderBy('position'),
            'pagination'=>false,
            'sort'=>false
        ]);
        
        $post = Yii::$app->request->post('data');
        if ($post) { 
            foreach ($post as $pos=>$id) { 
                AnketaQuestion::findOne($id)->updateAttributes(['position'=>$pos]);
            }
            return $this->redirect(['index', 'anketa_id'=>$anketa_id]);            
        }
        
        return $this->render('sort', [
            'dataProvider'=>$dataProvider,
            'anketa_id'=>$anketa_id
        ]);
    }
    
    public function actionToggleStatus($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['status'=>!$model->status]);

        return $this->redirect(['index', 'anketa_id'=>$model->anketa_id]);
    }
    
    public function actionParentAnswers()
    {
        Yii::$app->response->format = 'json';
        $parent_id = Yii::$app->request->post('parent_id');
        $answers = AnketaAnswer::find()->where(['question_id'=>$parent_id])->all();
        $data = [['id'=>'', 'text'=>'']];
        
        foreach ($answers as $answer) {
            $data[] = ['id'=>$answer->id, 'text'=>$answer->name];
        }
        
        return ['data'=>$data];
    }


    protected function findModel($id)
    {
        if (($model = AnketaQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена');
    }
}