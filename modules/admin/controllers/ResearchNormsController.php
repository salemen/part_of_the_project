<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\research\ResearchIndex;
use app\models\research\ResearchNormsCol;
use app\models\research\ResearchNormsQual;

class ResearchNormsController extends Controller
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

    public function actionIndex($index_id)
    {
        $index = $this->findIndex($index_id);
        
        if ($index->grade_id == ResearchIndex::GRADE_COL) {
            $query = ResearchNormsCol::find()->where(['index_id'=>$index->id]);
        } elseif ($index->grade_id == ResearchIndex::GRADE_QUAL) {
            $query = ResearchNormsQual::find()->where(['index_id'=>$index->id]);
        } else {
            throw new NotFoundHttpException('Показатель не найден.');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'index'=>$index
        ]);
    }

    public function actionCreate($index_id)
    {
        $index = $this->findIndex($index_id);
        
        if ($index->grade_id == ResearchIndex::GRADE_COL) {
            $model = new ResearchNormsCol(['index_id'=>$index_id]);
        } elseif ($index->grade_id == ResearchIndex::GRADE_QUAL) {
            $model = new ResearchNormsQual(['index_id'=>$index_id]);
        } else {
            throw new NotFoundHttpException('Показатель не найден.');
        }

        if ($index->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && $index->save() && $model->save()) {
            return $this->redirect(['index', 'index_id'=>$model->index_id]);
        }

        return $this->render('_form', [
            'index'=>$index,
            'model'=>$model
        ]);
    }

    public function actionUpdate($id, $index_id)
    {        
        $index = $this->findIndex($index_id);
        $model = $this->findModel($id, $index);        

        if ($index->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && $index->save() && $model->save()) {
            return $this->redirect(['index', 'index_id'=>$model->index_id]);
        }

        return $this->render('_form', [
            'index'=>$index,
            'model'=>$model
        ]);
    }

    public function actionDelete($id, $index_id)
    {
        $index = $this->findIndex($index_id);
        $model = $this->findModel($id, $index);
        $model->delete();

        return $this->redirect(['index', 'index_id'=>$model->index_id]);
    }
    
    public function actionToggleStatus($id, $index_id)
    {
        $index = $this->findIndex($index_id);
        $model = $this->findModel($id, $index);
        $model->updateAttributes(['status'=>($model->status == 0) ? 10 : 0]);

        return $this->redirect(['index', 'index_id'=>$model->index_id]);
    }
    
    protected function findIndex($id)
    {
        if (($model = ResearchIndex::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    } 
    
    protected function findModel($id, $index)
    {
        if ($index->grade_id == ResearchIndex::GRADE_COL) {
            if (($model = ResearchNormsCol::findOne($id)) !== null) {
                return $model;
            }
        } elseif ($index->grade_id == ResearchIndex::GRADE_QUAL) {
            if (($model = ResearchNormsQual::findOne($id)) !== null) {
                return $model;
            }
        }
        
        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}