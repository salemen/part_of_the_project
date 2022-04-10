<?php
// Получение данных в select2 ajax/post-запросом 

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\geo\GeoCity;
use app\models\employee\Employee;
use app\models\other\Mkb10;
use app\models\patient\Patient;
use app\models\research\ResearchUnit;
use app\models\vaccine\VacVaccine;

class DataController extends Controller
{        
    public function actionCity($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];
        
        if ($query) {
            $model = new Query();
            $select = ($keytext) ? 'name AS id, name AS text' : 'id, name AS text';
            $model->select($select)
                ->from(GeoCity::tableName())
                ->where(['like', 'name', $query])
                ->orderBy('name')    
                ->limit(30);         
            $command = $model->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>GeoCity::find($id)->name
            ];
        }
        
        return $out;
    }


    public function actionCityProp($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';

        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];

        if ($query) {
            $model = new Query();
            $select = ($keytext) ? 'name AS id, name AS text' : 'id, name AS text';
            $model->select($select)
                ->from(GeoCity::tableName())
                ->where(['IN', 'id', [322, 92, 84, 328, 102, 234]])
                ->andWhere(['like', 'name', $query])
                ->orderBy('name')
                ->limit(30);
            $command = $model->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>GeoCity::find($id)->name
            ];
        }

        return $out;
    }
    
    public function actionEmployee($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];
        
        if ($query) {
            $model = new Query();
            $select = ($keytext) ? 'fullname AS id, fullname AS text' : 'id, fullname AS text';
            $model->select($select)
                ->from(Employee::tableName())
                ->where(['like', 'fullname', $query])
                ->orderBy('fullname')
                ->limit(30);
            $command = $model->createCommand();
            $data = $command->queryAll();           
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>Employee::find($id)->fullname
            ];
        }
        
        return $out;
    }
    
    public function actionMkb($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];
        
        if ($query) {
            $model = new Query();
            $select = ($keytext) ? ['CONCAT(code, " ", name) AS id', 'CONCAT(code, " ", name) AS text'] : ['id', 'CONCAT(code, " ", name) AS text'];
            $model->select($select)
                ->from(Mkb10::tableName())
                ->where(['like', 'code', $query])
                ->orWhere(['like', 'name', $query])
                ->orderBy('name')
                ->limit(30);
            $command = $model->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>Mkb10::find($id)->name
            ];
        }
        
        return $out;
    } 
    
    public function actionPatient($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];
        
        if ($query) {
            $model = new Query();
            $select = ($keytext) ? 'fullname AS id, fullname AS text, phone' : 'id, fullname AS text, phone';
            $model->select($select)
                ->from(Patient::tableName())
                ->where(['like', 'fullname', $query])
                ->orderBy('fullname')
                ->limit(30);
            $command = $model->createCommand();
            $data = $command->queryAll();           
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>Patient::find($id)->fullname
            ];
        }
        
        return $out;
    }
    
    public function actionPopulateFilter()
    {
        Yii::$app->response->format = 'json';
        
        $type = Yii::$app->request->post('type');
        $value = Yii::$app->request->post('value');
        
        $data = [['id'=>'', 'text'=>'']];
        
        if ($type === "city") {
            $model = Organization::find()->where(['city'=>$value, 'is_hidden'=>false, 'status'=>10])->groupBy('name')->orderBy('name')->all();
            
            if ($model) {
                foreach ($model as $option) {
                    $data[] = ['id'=>$option->id, 'text'=>$option->name];
                }
            }
        }
        
        if ($type === "org") {
            $model = Department::find()->where(['org_id'=>$value, 'status'=>10])->groupBy('name')->orderBy('name')->all();
            
            if ($model) {
                foreach ($model as $option) {
                    $data[] = ['id'=>$option->id, 'text'=>$option->name . ' (' . $option->address . ')'];
                }
            }
        }
        
        return ['data'=>$data];
    }      
    
    public function actionPopulateUnit()
    {
        Yii::$app->response->format = 'json';
        
        $value = Yii::$app->request->post('value');
        
        $data = [['id'=>'', 'text'=>'']];
        
        $model = ResearchUnit::find()->joinWith('researchRelations')->where(['index_id'=>$value, 'status'=>10])->orderBy('name')->all();

        if ($model) {
            foreach ($model as $option) {
                $data[] = ['id'=>$option->id, 'text'=>$option->name];
            }
        }
        
        return ['data'=>$data];
    } 
    
    public function actionVaccine($id = null, $query = null, $keytext = false)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];
        
        if ($query) {
            $model = new Query();
            $select = ($keytext) ? 'name AS id, name AS text' : 'id, name AS text';
            $model->select($select)
                ->from(VacVaccine::tableName())
                ->where(['like', 'name', $query])
                ->orderBy('name')
                ->limit(30);
            $command = $model->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = [
                'id'=>$id,
                'text'=>VacVaccine::find($id)->name
            ];
        }
        
        return $out;
    } 
}