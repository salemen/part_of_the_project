<?php
namespace app\modules\covid\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\covid\models\CovidMaps;
use yii\data\Pagination;
use app\modules\covid\models\HotlineArea;

class InfoController extends Controller
{
    public function actionIndex()
    {
        $db = Yii::$app->db_univer;
        $page = Yii::$app->request->get('page', 1);
        $limit = Yii::$app->request->get('per-page', 20);
        $from = ($page - 1) * $limit;

        $sql = "
            SELECT DISTINCT lib.id, lib.name, lib.created_at 
            FROM library lib 
            LEFT JOIN tag_relation tr ON lib.id = tr.model_id 
            LEFT JOIN tag ON tag.id = tr.tag_id
            WHERE tr.model_type = 10 
            AND tag.name IN ('коронавирус', 'covid-19') 
            ORDER BY created_at DESC
        ";

        $count = $db->createCommand("SELECT COUNT(*) as total FROM ({$sql}) a")->queryScalar();
        $values = $db->createCommand("{$sql} LIMIT {$from},{$limit}")->queryAll();

        $pagination = new Pagination([
            'totalCount'=>$count,
            'pageSize'=>$limit
        ]);

        $model1 = HotlineArea::find()->all();

        return $this->render('index', [
            'pagination'=>$pagination,
            'values'=>$values,
            'model1'=>$model1
        ]);

    }
    
    public function actionContent()
    {
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            Yii::$app->response->format = 'json';
            $model = $this->findModel($post['id']);
            
            switch ($post['type']) {
                case 'hospital':
                    $content = $model->covid_hospital;
                    break;
                case 'test':
                    $content = $model->covid_test;
                    break;
                case 'vaccine':
                    $content = $model->covid_vaccine;
                    break;
                default:
                    $content = null;
                    break;
            }
            
            return [
                'id'=>$post['id'],
                'content'=>$content
            ];
        }
    }
    
    public function actionMap($type)
    {
        if ($type === 'hospital') {
            $condition = ['OR',
                ['!=', 'covid_hospital', ''],
                ['!=', 'covid_hospital', null]
            ];
            $title = 'Респираторные госпитали';
        } elseif ($type === 'test') {
            $condition = ['OR',
                ['!=', 'covid_test', ''],
                ['!=', 'covid_test', null]
            ];
            $title = 'Где сделать КТ?';
        } elseif ($type === 'vaccine') {
            $condition = ['OR',
                ['!=', 'covid_vaccine', ''],
                ['!=', 'covid_vaccine', null]
            ];
            $title = 'Где сделать тест на COVID-19?';
        } else {
            throw new NotFoundHttpException('Запрошенная страница не найдена.');
        }
        
        $model = CovidMaps::find()
            ->where(['status'=>10])
            ->andWhere($condition)
            ->all();
        
        return $this->render('map', [
            'model'=>$model,
            'title'=>$title,
            'type'=>$type
        ]);
    }
    
    protected function findModel($id)
    {
        if (($model = CovidMaps::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}