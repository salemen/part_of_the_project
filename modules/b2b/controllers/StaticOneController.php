<?php

namespace app\modules\b2b\controllers;

use Yii;
use app\modules\b2b\models\EmployeeDegree;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\employee\Employee;
use app\models\cardio\Cardio;
use app\models\consult\Consult as ConsultModel;
use app\models\consult\search\Consult as ConsultSearch;

/**
 * StaticOneController implements the CRUD actions for EmployeeDegree model.
 */
class StaticOneController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all EmployeeDegree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeDegree::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSearch()
    {

        $query = ConsultSearch::find()->joinWith(['employee', 'patient', 'payment', 'consult']);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);

        if(!empty($_GET['Employee']['fullname'])) {
            $fullname = $_GET['Employee']['fullname'];
        }
       if(!empty($_GET['picker_period'])){
        $created_at = $_GET['picker_period'];
        if(empty($created_at)) {
        }elseif (!is_null($created_at) && strpos($created_at, ' '.'-'.' ') !== false) {
            list($start_e, $end_e) = explode(' '.'-'.' ', $created_at);
            if ($start_e == $end_e) { $end_e += 86400; }


        }}


        $searchModel = new ConsultSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!empty($fullname)&&!empty($start_e)) {
               $emp = Employee::find()->select('id')->where(['id' => $fullname]);
                $dataProvider->query->andWhere(['like', 'employee_id', $emp])
                    ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    ->andWhere(['>=', 'worker_id', 4])
                   // ->joinWith('cardios')
                   ->orderBy(['created_at' => SORT_ASC]);

                $card = Cardio::find()->where(['employee_id' => $fullname])
                    ->andWhere(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all(); ?>


        <?php  }elseif (!empty($fullname)&&empty($start_e)&&empty($created_at)) {
               $dataProvider->query->andWhere(['employee_id' => $fullname])->andWhere(['>=', 'worker_id', 4])
               ->orderBy(['created_at' => SORT_DESC]);

               $card = Cardio::find()->where(['employee_id' => $fullname])->all();

        }elseif (empty($fullname)&&!empty($start_e)) {
            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);


         }else{
            $dataProvider->query->andWhere(['is_payd' => true])
                ->andWhere(['>=', 'worker_id', 4])->orderBy(['created_at' => SORT_ASC]);
            if (!empty($fullname)) {
                $card = Cardio::find()->where(['employee_id' => $fullname])->all();
            }else {$card = 0;}
        }


        return $this->render('search', [
            'dataProvider'=>$dataProvider,
            'card'=>$card,
            'searchModel'=>$searchModel
        ]);
    }


  
    protected function findModel($id)
    {
        if (($model = EmployeeDegree::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
