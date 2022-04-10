<?php
namespace app\modules\med\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\pz\search\RentgenJurnal as RentgenJurnalSearch;
use app\models\pz\RentgenJurnal;

class RentgenController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new RentgenJurnalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['r_id'=>SORT_DESC]);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model'=>$this->findModel($id)
        ]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->can('permRentgen')) {
            Yii::$app->session->setFlash('rentgenForbidder', [
                'title'=>'Внимание!',
                'content'=>'У вас нет доступа к функционалу "Рентгенография".',
                'type'=>'orange'
            ]);
            
            return $this->redirect(['index']);
        }
        
        $model = new RentgenJurnal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id)
    {
        $user = Yii::$app->user;
        
        if (!$user->can('permRentgen')) {
            Yii::$app->session->setFlash('rentgenForbidder', [
                'title'=>'Внимание!',
                'content'=>'У вас нет доступа к функционалу "Рентгенография".',
                'type'=>'orange'
            ]);
            
            return $this->redirect(['index']);
        }
        
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('permRentgen')) {
            Yii::$app->session->setFlash('rentgenForbidder', [
                'title'=>'Внимание!',
                'content'=>'У вас нет доступа к функционалу "Рентгенография".',
                'type'=>'orange'
            ]);
            
            return $this->redirect(['index']);
        }
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionExport()
    {
        $model = new DynamicModel(['start_date', 'end_date']);
        $model->setAttributeLabels(['start_date'=>'Дата, от', 'end_date'=>'Дата, до'])
            ->addRule(['start_date'], 'required', ['message'=>'Необходимо заполнить «Дата, от».'])
            ->addRule(['end_date'], 'required', ['message'=>'Необходимо заполнить «Дата, до».']);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dataProvider = new ActiveDataProvider([
                'query'=>RentgenJurnal::find()->where(['AND', ['>=', 'r_data', implode(' ', [$model->start_date, '00:00:00'])], ['<=', 'r_data', implode(' ', [$model->end_date, '00:00:00'])]])->orderBy('r_data'),
                'pagination'=>false,
                'sort'=>false
            ]);
            
            if (count($dataProvider->getModels()) > 0) {
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=" . date('U') . ".xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                return $this->renderPartial('_export/grid', [
                    'dataProvider'=>$dataProvider
                ]);
            } else {
                Yii::$app->session->setFlash('exportNoData', [
                     'title'=>'Внимание!',
                     'content'=>'Данных за указанный период не найдено.',
                     'type'=>'orange'
                 ]);
            }
        }        
        
        return $this->render('_export/index', [
            'model'=>$model
        ]);               
    }
    
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        
        return $this->renderAjax('print', [
            'model'=>$model
        ]);
    }

    protected function findModel($id)
    {
        if (($model = RentgenJurnal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}