<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\consult\search\Consult as ConsultSearch;
use app\models\patient\search\Patient as PatientSearch;
use app\models\payments\search\PaymentsOnline as PaymentsOnlineSearch;

class StatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['*'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['statist']
                    ]
                ]
            ]
        ];
    }
    
    public function actionConsult()
    {
        $searchModel = new ConsultSearch();
        $dataProvider = $searchModel->searchStat(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['is_payd'=>true, 'isTest'=>false])
            ->orderBy(['created_at'=>SORT_DESC]);

        return $this->render('consult', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }
    
    public function actionPatient()
    {
        $searchModel = new PatientSearch();
        $dataProvider = $searchModel->searchStat(Yii::$app->request->queryParams);
        $dataProvider->query
            ->orderBy(['created_at'=>SORT_DESC]);
                
        return $this->render('patient', [            
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }
    
    public function actionPaymentsOnline()
    {
        $searchModel = new PaymentsOnlineSearch();
        $dataProvider = $searchModel->searchStat(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['is_test'=>false])
            ->orderBy(['created_at'=>SORT_DESC]);

        return $this->render('payments-online', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }
}