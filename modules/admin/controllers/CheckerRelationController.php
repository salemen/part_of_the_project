<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\checker\CheckerRelation;

class CheckerRelationController extends Controller
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

    public function actionIndex($symptom_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>CheckerRelation::find()->joinWith(['bodypart'])->where(['symptom_id'=>$symptom_id])->orderBy('name'),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'symptom_id'=>$symptom_id
        ]);
    }

    public function actionCreate($symptom_id)
    {
        $model = new CheckerRelation([
            'symptom_id'=>$symptom_id
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'symptom_id'=>$model->symptom_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($bodypart_id, $symptom_id)
    {
        $model = $this->findModel($bodypart_id, $symptom_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'symptom_id'=>$model->symptom_id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionDelete($bodypart_id, $symptom_id)
    {
        $model = $this->findModel($bodypart_id, $symptom_id);
        $model->delete();

        return $this->redirect(['index', 'symptom_id'=>$model->symptom_id]);
    }

    protected function findModel($bodypart_id, $symptom_id)
    {
        if (($model = CheckerRelation::findOne(['bodypart_id'=>$bodypart_id, 'symptom_id'=>$symptom_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}