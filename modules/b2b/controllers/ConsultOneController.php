<?php

namespace app\modules\b2b\controllers;

use Yii;
use app\modules\b2b\models\Consult_one;
use app\modules\b2b\models\Consult_oneSearch;
use app\modules\b2b\models\Cardio_one;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\employee\Employee;




class ConsultOneController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new Consult_oneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {


        if(!empty($_GET['Employee']['fullname'])) {
            $fullname = $_GET['Employee']['fullname'];
            $card = Cardio_one::find()->where(['employee_id' => $fullname])->all();
        }
        if(!empty($_GET['picker_period'])){
            $created_at = $_GET['picker_period'];
            if(empty($created_at)) {
            }elseif (!is_null($created_at) && strpos($created_at, ' '.'-'.' ') !== false) {
                list($start_e, $end_e) = explode(' '.'-'.' ', $created_at);
                if ($start_e == $end_e) { $end_e += 86400; }
                $card = Cardio_one::find()->where(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();

            }}


        $searchModel = new Consult_oneSearch();


        if(!empty($fullname)&&!empty($created_at)) {
            $card = Cardio_one::find()->where(['employee_id' => $fullname])
                ->andWhere(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();
        }

        if (!empty($card)) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $card);
        }else{$dataProvider = $searchModel->search(Yii::$app->request->queryParams);}


        if (!empty($fullname)&&!empty($start_e)) {
            $emp = Employee::find()->select('id')->where(['id' => $fullname]);
            $dataProvider->query->andWhere(['like', 'employee_id', $emp])
                ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);

                 }elseif (!empty($fullname)&&empty($start_e)&&empty($created_at)) {
                     $dataProvider->query->andWhere(['employee_id' => $fullname])->andWhere(['>=', 'worker_id', 4])
                    ->orderBy(['created_at' => SORT_DESC]);

                 }elseif (empty($fullname)&&!empty($start_e)) {
                     $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    ->andWhere(['>=', 'worker_id', 4])
                    ->orderBy(['created_at' => SORT_ASC]);


                }else{
                    $dataProvider->query->andWhere(['is_payd' => true])
                        ->andWhere(['>=', 'worker_id', 4])->orderBy(['created_at' => SORT_ASC]);

                }


        return $this->render('search', [
            'dataProvider'=>$dataProvider,
           // 'card'=>$card,
            'searchModel'=>$searchModel,
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице

            ],
        ]);
    }

    public function actionIndexhold()
    {
        $searchModel = new Consult_oneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexhold', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSearchhold()
    {
        if(!empty($_GET['Employee']['fullname'])) {
            $fullname = $_GET['Employee']['fullname'];
            $card = Cardio_one::find()->where(['employee_id' => $fullname])->all();
        }
        if(!empty($_GET['picker_period'])){
            $created_at = $_GET['picker_period'];
            if(empty($created_at)) {
            }elseif (!is_null($created_at) && strpos($created_at, ' '.'-'.' ') !== false) {
                list($start_e, $end_e) = explode(' '.'-'.' ', $created_at);
                if ($start_e == $end_e) { $end_e += 86400; }
                $card = Cardio_one::find()->where(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();

            }}


        $searchModel = new Consult_oneSearch();


        if(!empty($fullname)&&!empty($created_at)) {
            $card = Cardio_one::find()->where(['employee_id' => $fullname])
                ->andWhere(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();
        }

        if (!empty($card)) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $card);
        }else{$dataProvider = $searchModel->search(Yii::$app->request->queryParams);}

        if (!empty($fullname)&&!empty($start_e)) {
            $emp = Employee::find()->select('id')->where(['id' => $fullname]);
            $dataProvider->query->andWhere(['like', 'employee_id', $emp])
                ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->joinWith('history')
                ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);

        }elseif (!empty($fullname)&&empty($start_e)&&empty($created_at)) {
            $dataProvider->query->andWhere(['employee_id' => $fullname])->andWhere(['>=', 'worker_id', 4])
                ->joinWith('history')
                ->orderBy(['created_at' => SORT_DESC]);

        }elseif (empty($fullname)&&!empty($start_e)) {
            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->joinWith('history')
                ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);


        }else{
            $dataProvider->query->andWhere(['is_payd' => true])->joinWith('history')
                ->andWhere(['>=', 'worker_id', 4])->orderBy(['created_at' => SORT_ASC]);

        }


        return $this->render('searchhold', [
            'dataProvider'=>$dataProvider,
            // 'card'=>$card,
            'searchModel'=>$searchModel,
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице

            ],
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Consult_one::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
