<?php

namespace app\modules\b2b\controllers;

use app\modules\b2b\models\Consult;
use app\models\consult\Consult as ConsultDep;
use app\modules\b2b\models\Consult_oneSearch;
use Yii;
use app\models\consult\ConsultDepartment;
use app\models\consult\search\SearchDepartment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\data\Department;
use app\models\employee\EmployeePosition;
use app\models\data\Organization;


class ConsultDepartmentController extends Controller
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
        $searchModel = new SearchDepartment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSearch()
    {


        if(!empty($_GET['Department']['name'])) {
            $id1 = $_GET['Department']['name'];
            $id = $id1[0];
            $name1 = Department::find()->select('id')->where(['id' => $id])->all();
            if(!empty($name1[0]['id'])){
                $name = $name1[0]['id'];
//                $eml1 = EmployeePosition::find()->select('employee_id')->where(['org_id' => $name])->all();
                $eml = Consult::find()->select('employee_id')->where(['dep_id' => $name])->all();
                var_dump($name);
            }


        }
        if(!empty($_GET['picker_period'])){
            $created_at = $_GET['picker_period'];
            if(empty($created_at)) {
            }elseif (!is_null($created_at) && strpos($created_at, ' '.'-'.' ') !== false) {
                list($start_e, $end_e) = explode(' '.'-'.' ', $created_at);
                if ($start_e == $end_e) { $end_e += 86400; }
               // $card = Cardio_one::find()->where(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();

            }}


        $searchModel = new SearchDepartment();

       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        if (!empty($id)&&!empty($start_e)) {
            $dataProvider->query->where(['employee_id' => $eml])
                ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
//                ->andWhere(['worker_id' => 2])
                ->orderBy(['created_at' => SORT_ASC]);

        }elseif (!empty($id)&&empty($start_e)&&empty($created_at)) {
            $dataProvider->query->where(['employee_id' => $eml])//->andWhere(['worker_id' => 2])
                ->orderBy(['created_at' => SORT_DESC]);

        }elseif (empty($id)&&!empty($start_e)) {
            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
//                ->andWhere(['worker_id' => 2])
                ->orderBy(['created_at' => SORT_ASC]);

        }


        return $this->render('_search', [
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
            $id1 = $_GET['Employee']['fullname'];
            $id = $id1[0];
            $name1 = Department::find()->select('id')->where(['id' => $id])->all();
            if(!empty($name1[0]['id'])){
                $name = $name1[0]['id'];
//                $eml1 = EmployeePosition::find()->select('employee_id')->where(['org_id' => $name])->all();
                $eml = Consult::find()->select('employee_id')->where(['dep_id' => $name])->all();
                var_dump($name);
            }

        }

        if(!empty($_GET['picker_period'])){
            $created_at = $_GET['picker_period'];
            if(empty($created_at)) {
            }elseif (!is_null($created_at) && strpos($created_at, ' '.'-'.' ') !== false) {
                list($start_e, $end_e) = explode(' '.'-'.' ', $created_at);
                if ($start_e == $end_e) { $end_e += 86400; }
                // $card = Cardio_one::find()->where(['between', 'created_at', strtotime($start_e), strtotime($end_e)])->all();

            }}
        
        $searchModel = new SearchDepartment();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        if(!empty($_GET['uder'])) {
            $uder = $_GET['uder'];

        }

        if (!empty($id)&&!empty($start_e)&&empty($uder)) {
            $dataProvider->query->where(['employee_id' => $eml])
                ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                // ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);

        }elseif (!empty($id)&&empty($start_e)&&empty($created_at)&&empty($uder)) {
            $dataProvider->query->where(['employee_id' => $eml])//->andWhere(['>=', 'worker_id', 4])
            ->orderBy(['created_at' => SORT_DESC]);

        }elseif (empty($id)&&!empty($start_e)&&empty($uder)) {
            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                // ->andWhere(['>=', 'worker_id', 4])
                ->orderBy(['created_at' => SORT_ASC]);

        }


            if (!empty($id)&&!empty($start_e)&&!empty($uder)) {
                $dataProvider->query->where(['employee_id' => $eml])
                    ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    // ->andWhere(['>=', 'worker_id', 4])
                    ->andWhere(['is_canceled' => 1])
                    ->orderBy(['created_at' => SORT_ASC]);

            }elseif (!empty($id)&&empty($start_e)&&empty($created_at)&&!empty($uder)) {
                $dataProvider->query->where(['employee_id' => $eml])//->andWhere(['>=', 'worker_id', 4])
                ->andWhere(['is_canceled' => 1])
                ->orderBy(['created_at' => SORT_DESC]);

            }elseif (empty($id)&&!empty($start_e)&&!empty($uder)) {
                $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    // ->andWhere(['>=', 'worker_id', 4])
                    ->andWhere(['is_canceled' => 1])
                    ->orderBy(['created_at' => SORT_ASC]);

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


    public function actionStatDep($org = null, $period = null)
    {
        {
            $data = $this->getStatistic($org, $period);
            $title = 'Статистика консультаций по подразделениям';

            return $this->render('statdep', [
                'data'=>$data,
                'org'=>$org,
                'title'=>$title,
                'period'=>$period
            ]);
        }
    }
    
    
    protected function findModel($id)
    {
        if (($model = ConsultDepartment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getStatistic($org, $period)
    {
        $result = [];

        $query = ConsultDep::find()
            ->select('dep_id')
            ->joinWith('payment')
            ->where([
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ]);

        $depIds = [];
        $deps = ($org) ? Department::findAll(['org_id'=>$org]) : Department::find()->where(['IN', 'org_id', EmployeePosition::getOrgIds()])->all();
        if ($deps) {
            foreach ($deps as $dep) {
                array_push($depIds, $dep->id);
            }
        }

        $query->andWhere(['IN', 'dep_id', $depIds]);

        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }

            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }

        $query->distinct();

        $model = $query->all();

        if ($model) {
            foreach ($model as $key=>$value) {
               // $backgroundColor = AppHelper::generateHex($key);
                $dep_id = $value->dep_id;
                $dep_name = $value->department->name;

                $result['labels'][$key] = $dep_name;

              
            }
        }

        return $result;
    }
}
