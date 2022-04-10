<?php
namespace app\modules\med\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\anketa\search\Anketa as AnketaSearch;

class AnketaController extends Controller
{        
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                         
                        'matchCallback'=>function() {
                            return Yii::$app->session->has('employee_santal');
                        }
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {
        $searchModel = new AnketaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
}