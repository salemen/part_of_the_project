<?php
// Раздел "Специальные консультации"

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeeDocumentOther;
use app\models\employee\EmployeePosition;
use app\models\employee\search\EmployeeAdvisor as EmployeeAdvisorSearch;

class DoctorSpecialController extends Controller
{       
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index', 'view'],
                        'allow'=>Yii::$app->params['specialConsult']['active'],
                        'denyCallback'=>function($rule, $action) {
                            Yii::$app->response->redirect(['doctor']);
                        }
                    ],
                    [
                        'actions'=>['consult-details'],
                        'allow'=>true,
                        'matchCallback'=>function ($rule, $action) {
                            $user = Yii::$app->user;
                            if (!Yii::$app->params['specialConsult']['active']) { return false; }
                            if ($user->isGuest) { return false; }
                            return true;
                        }
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $searchModel = new EmployeeAdvisorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        
        $dataProvider->query
            ->andWhere(['employee.status'=>10, 'employee_advisor.status'=>10, 'employee_advisor.is_special'=>true])
            ->orderBy(['position'=>SORT_DESC])
            ->distinct();
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'model'=>$dataProvider->getModels(),
            'pagination'=>$dataProvider->pagination
        ]);
    } 
    
    public function actionView($id)
    {
        if (($model = $this->findModelByFullname($id)) !== null) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }
        
        $model = $this->findModel($id);
        $posProvider = new ActiveDataProvider([
            'query'=>EmployeePosition::find()->where(['employee_id'=>$model->id]),
            'sort'=>false
        ]);
        $qualProvider = new ActiveDataProvider([
            'query'=>EmployeeDocument::find()
                ->select(['employee_id', 'empl_qual', 'empl_spec'])
                ->where(['doc_type'=>'Сертификат', 'employee_id'=>$model->id])
                ->distinct('empl_qual'),
            'sort'=>false
        ]);
        $docProvider = new ActiveDataProvider([
            'query'=>EmployeeDocumentOther::find()
                ->select(['employee_id', 'empl_spec', 'doc_type'])
                ->where(['employee_id'=>$model->id])
                ->distinct('empl_qual'),
            'sort'=>false
        ]);        
        
        return $this->render('view', [
            'model'=>$model,
            'posProvider'=>$posProvider,
            'qualProvider'=>$qualProvider,
            'docProvider'=>$docProvider
        ]);
    }
    
    public function actionConsultDetails($id)
    {            
        $model = $this->findModel($id);
        
        if (Consult::isConsultExist($model->id, Yii::$app->user->id, true)) {
            Yii::$app->session->setFlash('consultExist', [
                'title'=>'Внимание!',
                'content'=>'Консультация с данным специалистом уже существует. Пожалуйста, оплатите/отмените или завершите консультацию',
                'type'=>'orange'
            ]);
            return $this->redirect(['/consult']);
        }
        
        return $this->renderAjax('_part/consult-details', [
            'model'=>$model
        ]);
    }     
    
    protected function findModel($id)
    {        
        if (($model = Employee::find()->joinWith(['advisor', 'positionsActive'])->where(['employee.id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }         
    
    protected function findModelByFullname($fullname)
    {
        if (($model = Employee::findOne(['fullname'=>trim($fullname)])) !== null) {
            return $model;
        }
        
        return null;
    }        
}