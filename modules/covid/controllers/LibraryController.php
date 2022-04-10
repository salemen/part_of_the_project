<?php
namespace app\modules\covid\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;

class LibraryController extends Controller
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
        
        return $this->render('index', [
            'pagination'=>$pagination,
            'values'=>$values
        ]);
    }
}