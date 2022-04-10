<?php
namespace app\modules\med\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\models\template\Template;
use app\models\template\search\Template as TemplateSearch;

class TemplateController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [            
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionCreate($type_id)
    {
        $model = new Template(['type_id'=>$type_id]);
        $model2 = $this->findRelatedModel($model);

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) && $this->saveAll($model, $model2)) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model,
            'model2'=>$model2
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model2 = $this->findRelatedModel($model);

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) && $this->saveAll($model, $model2)) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model,
            'model2'=>$model2
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    } 
    
    protected function findRelatedModel($templateModel)
    {
        $template_id = $templateModel->id;
        $type_id = $templateModel->type_id;
        $relation = Template::getRelatedModels()[$type_id];
        
        if ($relation) {
            $className = $relation['className'];
            $model = ($templateModel->isNewRecord) ? new $className() : $className::findOne(['template_id'=>$template_id]);
                
            if ($model !== null) {
                return $model;
            }
            
            throw new NotFoundHttpException('Запрошенная страница не найдена.');            
        }

        throw new NotFoundHttpException('Запрошенный бланк осмотра не найден.');
    }
    
    protected function saveAll($model, $model2)
    {
        if (!$model->validate()) {
            return false;
        }
        
        if (!$model2->validate()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
            
        if (!$model->save()) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Ошибка при сохранении бланка осмотра.');
        }

        $model2->template_id = $model->id;
        $model2->recombine();
        
        if (!$model2->save()) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Ошибка при сохранении данных бланка.');
        }
        
        $transaction->commit();
        
        return true;
    }        
}