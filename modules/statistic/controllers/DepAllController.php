<?php
namespace app\modules\statistic\controllers;

use app\models\data\Organization;
use app\models\employee\Employee;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\consult\search\Consult;
use app\models\consult\search\Consult as ConsultModel;
use app\models\data\Department;
use app\models\consult\search\SearchDepartment;
use app\modules\b2b\models\Consult_one;
use app\modules\b2b\models\Consult_oneSearch;
use yii\web\NotFoundHttpException;


class DepAllController extends Controller
{

    public function actionIndex()
    {

        $get = Yii::$app->request->get();
        if (empty($get['Employee']['fullname'])&&empty($get['all'])){
            return $this->render('index');
        }

            if (!empty($get['picker_period'])) {
                $created_at = $get['picker_period'];
                if (empty($created_at)) {
                } elseif (!is_null($created_at) && strpos($created_at, ' ' . '-' . ' ') !== false) {
                    list($start_e, $end_e) = explode(' ' . '-' . ' ', $created_at);
                    if ($start_e == $end_e) {
                        $end_e += 86400;
                    }
                }
            }

        $searchModel = new SearchDepartment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            if(!empty($get['uder'])) {
                $uder = $get['uder'];

            }
        if(!empty($get['all'])) {
            $all = $get['all'];
            if(!empty($all)&&!empty($start_e)){
                $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    ->orderBy(['employee_id' => SORT_ASC]);
            }
        }elseif (!empty($start_e)&& !empty($uder)&&empty($all)) {

            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->andWhere(['is_canceled' => '1'])
                ->orderBy(['employee_id' => SORT_ASC]);

        } elseif (empty($start_e) && !empty($uder)&&empty($all)) {

            $dataProvider->query->andWhere(['is_canceled' => '1'])
                ->orderBy(['employee_id' => SORT_ASC]);

        } elseif (isset($start_e)&& !isset($uder)&&empty($all)) {

            $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                ->andWhere(['is_end' => 1])
                ->andWhere(['is_payd' => 1])
                ->andWhere(['is_canceled' => '0'])
                ->orderBy(['employee_id' => SORT_ASC]);

        } elseif (empty($start_e) && empty($created_at)&& empty($uder)&&empty($all)) {

            $dataProvider->query->andWhere(['is_end' => 1])
                ->andWhere(['is_payd' => 1])
                ->andWhere(['is_canceled' => '0'])
                ->orderBy(['employee_id' => SORT_ASC]);

        } elseif (empty($start_e)&& empty($uder)&&empty($all)) {

            $dataProvider->query->andWhere(['is_end' => 1])
                ->andWhere(['is_payd' => 1])
                ->andWhere(['is_canceled' => '0'])
                ->orderBy(['employee_id' => SORT_ASC]);

        } else {
            return "Ничего нет";
        }

        if (!empty($get['Employee']['fullname'])) {
            $value = $get['Employee']['fullname'];
            $org = Department::find()->select('id')->andWhere(['org_id' => $value])->all();
            $dep = Organization::find()->select('name')->andWhere(['id' => $value])->all();
        }else{
            $org = "Организаций нет";
        }

        if(!isset($all)) {
            foreach ($org as $keys => $names) {
                foreach ($names as $key => $name) {
                    $value3[$key][] = $name;
                    continue;
                }
            }

            $organ = Department::find()->select("name")->andWhere(['id' => $value3['id']])->all();
            foreach ($organ as $keys => $names) {
                foreach ($names as $key => $name) {
                    $organization[] = $name;
                    continue;
                }
            }
            foreach ($organization as $names) {
                $namess[] = $names;
            }
            $caption = implode('&nbsp;', $namess);
            $caption = $dep[0]['name'] . ' — ' . $caption;
        }

        if(!empty($all)){
            return $this->render('_searchall', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        if(empty($get['uder'])&&empty($all)){
            return $this->render('_search', [
                'searchModel' => $searchModel,
                'caption' => $caption,
                'dep' => $dep,
                'dataProvider' => $dataProvider,
            ]);
        }
        if(!empty($get['uder'])&&empty($all)){
            return $this->render('_searchuder', [
                'searchModel' => $searchModel,
                'caption' => $caption,
                'dep' => $dep,
                'dataProvider' => $dataProvider,
            ]);
        }
       
    }

    public function actionSearch()
    {
        
        $searchModel = new SearchDepartment();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $get = Yii::$app->request->get();

        if (empty($get['Employee']['fullname'])){
          return $this->render('index');
          }
        $value = $get['Employee']['fullname'];
        if(!empty($value)) {


            $org = \app\models\data\Department::find()->select('id')->andWhere(['org_id' => $value])->all();
            $value2 = Array();
            foreach ($org as  $item) {
                $value2[] = $item;
            }

            foreach ($value2 as $keys => $names) {
                foreach ($names as $key => $name) {
                    $value3[$key][] = $name;
                    continue;
                }
            }

            error_reporting(0);

            if (!empty($get['picker_period'])) {
                $created_at = $get['picker_period'];
                if (empty($created_at)) {
                } elseif (!is_null($created_at) && strpos($created_at, ' ' . '-' . ' ') !== false) {
                    list($start_e, $end_e) = explode(' ' . '-' . ' ', $created_at);
                    if ($start_e == $end_e) {
                        $end_e += 86400;
                    }
                }
            }

            if(!empty($get['uder'])) {
                $uder = $get['uder'];

            }

            if (!empty($value3) && !empty($start_e)&& !empty($uder)) {

                    $dataProvider->query->where(['dep_id'=> $value3['id']])
                        ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                        ->andWhere(['is_canceled' => '1'])
                        ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (!empty($value3) && empty($start_e) && empty($created_at)&& !empty($uder)) {

                    $dataProvider->query->where(['dep_id'=> $value3['id']])
                        ->andWhere(['is_canceled' => '1'])
                       ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (empty($value3) && !empty($start_e)&& !empty($uder)) {

                    $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                       ->andWhere(['is_canceled' => '1'])

                        ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (empty($value3) && empty($start_e)&& !empty($uder)) {

                $dataProvider->query->andWhere(['is_canceled' => '1'])
                    //->andWhere(['worker_id' => 2,3])
                    ->orderBy(['employee_id' => SORT_ASC]);

            }elseif (!empty($value3) && !empty($start_e)&& empty($uder)) {

                $dataProvider->query->where(['dep_id'=> $value3['id']])
                    ->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    ->andWhere(['is_end' => 1])->andWhere(['is_payd' => 1])->andWhere(['is_canceled' => '0'])
                    //->andWhere(['worker_id' => 2,3])
                    ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (!empty($value3) && empty($start_e) && empty($created_at)&& empty($uder)) {

                $dataProvider->query->where(['dep_id'=> $value3['id']])
                    ->andWhere(['is_end' => 1])->andWhere(['is_payd' => 1])->andWhere(['is_canceled' => '0'])
                    // ->andWhere(['employee.worker_id' => 2])
                    ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (empty($value3) && !empty($start_e)&& empty($uder)) {

                $dataProvider->query->andWhere(['between', 'consult.created_at', strtotime($start_e), strtotime($end_e)])
                    ->andWhere(['is_end' => 1])->andWhere(['is_canceled' => '0'])
                    //->andWhere(['worker_id' => 2,3])
                    ->orderBy(['employee_id' => SORT_ASC]);

            } elseif (empty($value3) && empty($start_e)&& empty($uder)) {

                $dataProvider->query->andWhere(['is_end' => 1])->andWhere(['is_canceled' => '0'])
                    //->andWhere(['worker_id' => 2,3])
                    ->orderBy(['employee_id' => SORT_ASC]);

            } else {
                    return "Ничего нет";
                }
        }

        if (empty($dataProvider)){
            exit;
        }


        foreach ($org as $keys => $names) {
            foreach ($names as $key => $name) {
                $value3[$key][] = $name;
                continue;
            }
        }
        $organ = Department::find()->select("name")->andWhere(['id' => $value3['id']])->all();
        foreach ($organ as $keys => $names) {
            foreach ($names as $key => $name) {
                $organization[] = $name;
                continue;
            }
        }
        foreach ($organization as $names) {
            $namess[] = $names;
        }
        $caption = implode('&nbsp;', $namess);


            return $this->render('_search', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'caption'=> $caption,
                'org'=>$org,

            ]);

    }



    protected function getStatistic($org, $period)
    {
        $result = [];

        $query = Consult::find()
            ->select('dep_id')
            ->joinWith('payment')
            ->where([
                'is_canceled'=>false,
                'is_payd'=>true,
                'isTest'=>false
            ])
            ->andWhere(['NOT', ['dep_id'=>null]]);

        if ($org) {
            $depIds = [];
            $deps = Department::findAll(['org_id'=>$org]);
            if ($deps) {
                foreach ($deps as $dep) {
                    array_push($depIds, $dep->id);
                }
            }

            $query->andWhere(['IN', 'dep_id', $depIds]);
        }

        if ($period) {
            list($start_date, $end_date) = explode('-', $period);
            if ($start_date == $end_date) { $end_date += 86400; }

            $query->andWhere(['between', 'created_at', $start_date, $end_date]);
        }

        $query->distinct();

        $model = $query->all();

        if ($model) {
            foreach ($model as $key=>$value) {
                $backgroundColor = AppHelper::generateHex($key);
                $dep_id = $value->dep_id;
                if (!empty($value->department->name)){
                    $dep_name = $value->department->name;

                }

                $result['labels'][$key] = $dep_name;
                $result['datasets'][0]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][0]['data'][$key] = Consult::getConsultCountByParams(null, $dep_id, $period);
                $result['datasets'][0]['label'][$key] = $dep_name;
                $result['datasets'][1]['backgroundColor'][$key] = $backgroundColor;
                $result['datasets'][1]['data'][$key] = Consult::getConsultSumByParams(null, $dep_id, $period);
                $result['datasets'][1]['label'][$key] = $dep_name;
            }
        }

        return $result;
    }

    protected function findModel($id)
    {
        if (($model = Consult_one::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}