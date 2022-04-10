<?php


namespace app\controllers;

use yii\db\Query;
use app\models\employee\Employee;
use yii\web\Controller;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\oms\Oms;
use app\models\geo\GeoCity;

use Yii;

class CartController extends Controller
{


    
    public function actionDokList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query = Employee::find()->select('id, fullname AS text')->where(['Like', 'fullname', $q])
               ->andWhere(['>=', 'worker_id', 4])->limit(20);
            //$query->text = $query->name;
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Employee::find($id)->select('fullname')->all()];
        }
        return $out;
    }

    public function actionDokDep($q = null, $id = null)
    {
        $query = new Query;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $orgIds = EmployeePosition::getOrgIds();
            $orgArray = Organization::find()->where(['is_hidden'=>0, 'status'=>10])->andWhere(['IN', 'id', $orgIds])->orderBy('name')->all();

            $query = Department::find()->select(['id',"CONCAT(name, ' ', address ) AS text"])->where(['Like', 'name', $q])
                ->limit(30);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Department::find($id)->select('name')->all()];
        }
        return $out;
    }

    public function actionDokDepAll($q = null, $id = null)
    {
        $query = new Query;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $orgIds = EmployeePosition::getOrgIds();
            $orgArray = Organization::find()->where(['is_hidden'=>0, 'status'=>10])->andWhere(['IN', 'id', $orgIds])->orderBy('name')->all();

            $query = Organization::find()->select('id, name AS text')
                ->where(['Like', 'name', $q])
                ->andWhere(['in', 'id', [0,1,2,3,4,5,6,7,8,13,14,15,16,17,18]])
                ->limit(30);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Organization::find()->select('name')->all()];
        }
        return $out;
    }

    public function actionSubcat() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id = $parents[0];
                $city = GeoCity::find()->select('id')->where(['name'=> $id])->all();
                $out = Department::find()->select(['id',"CONCAT(name, ' ', address ) AS name"])->where(['reg_id' => $id])->asArray()->all();
                
                return ['output'=>$out, 'selected'=>''];
            }
        }
        $out = Organization::find()->select('id, name AS text')
            ->andWhere(['in', 'id', [0,1,2,3,4,5,6,7,8,13,14,15,16,17,18]])
            ->limit(30);
        return ['output'=>$out, 'selected'=>'4'];
    }

    public function actionPolisorg() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $dep = Oms::find()->select('oms')->distinct(true)->all();
        $out = ArrayHelper::map($dep,'oms','oms');
        foreach ($out as $key=>$item){
            $id=$key;
            $name=$item;
            $outarr[]=['id'=>$id, 'name'=>$name];
        }
        $data = ['out'=>$outarr, 'selected'=>''];
        return ['output'=>$data['out'], 'selected'=>$data['selected']];



    }

}