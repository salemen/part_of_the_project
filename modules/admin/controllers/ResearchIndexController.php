<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\research\ResearchIndex;
use app\models\research\search\ResearchIndex as ResearchIndexSearch;
use app\modules\admin\forms\ResearchNormsCopyForm;

class ResearchIndexController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'delete'=>['POST']
                ]
            ]
        ];
    }

    public function actionIndex($type_id)
    {
        $searchModel = new ResearchIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['type_id'=>$type_id])->orderBy(['name'=>SORT_ASC]);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'type_id'=>$type_id
        ]);
    }

    public function actionCreate($type_id)
    {
        $model = new ResearchIndex([
            'type_id'=>$type_id
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'type_id'=>$model->type_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'type_id'=>$model->type_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'type_id'=>$model->type_id]);
    }
    
    public function actionSort($type_id, $parent_id = null)
    {
        $model = ResearchIndex::find();
        $query = ($parent_id !== null) ? $model->where(['parent_id'=>$parent_id]) : $model->where(['IS', 'parent_id', null])->andWhere(['type_id'=>$type_id]);
        
        $dataProvider = new ActiveDataProvider([
            'query'=>$query->orderBy('position'),
            'pagination'=>false,
            'sort'=>false
        ]);
        
        $post = Yii::$app->request->post('data');
        if ($post) {
            foreach ($post as $pos=>$id) {
                $this->findModel($id)->updateAttributes(['position'=>$pos]);
            }
            return $this->redirect(['index', 'type_id'=>$type_id]);
        }
        
        return $this->render('sort', [
            'dataProvider'=>$dataProvider,
            'type_id'=>$type_id
        ]);
    }
    
    public function actionToggleStatus($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['status'=>($model->status == 0) ? 10 : 0]);

        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionCopyNorms($type_id)
    {
        $model = new ResearchNormsCopyForm(['type_id'=>$type_id]);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'type_id'=>$type_id]);
        }
        
        return $this->render('_form-copy-norms', [
            'model'=>$model,
            'type_id'=>$type_id
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ResearchIndex::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }    
}