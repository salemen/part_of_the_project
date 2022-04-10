<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use app\models\user\UserDiagnosis;

class DiagnosisController extends Controller
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
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>UserDiagnosis::find()->where(['patient_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->renderAjax('view', [
            'model'=>$model
        ]);
    } 
    
    public function actionCreate()
    {
        $model = new UserDiagnosis(['patient_id'=>Yii::$app->user->id]); 
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {         
                if ($model->save()) {         
                    return $this->redirect(['index']);
                } else { 
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }   
            
            return $this->renderAjax('_form', [
                'model'=>$model
            ]);
        }
        
        return $this->redirect(['index']);
    }   
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); 
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {         
                if ($model->save()) {         
                    return $this->redirect(['index']);
                } else { 
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }   
            
            return $this->renderAjax('_form', [
                'model'=>$model
            ]);
        }
        
        return $this->redirect(['index']);
    } 
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect(['index']);
    }        
    
    public function actionChart()
    {        
        $model = $this->getDiagnosisByCount();
                
        return $this->render('chart', [
            'model'=>$model
        ]);
    }        
    
    protected function findModel($id)
    {
        if (($model = UserDiagnosis::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }       
    
    protected function getDiagnosisByCount()
    {
        $result = [];        
        $patient_id = Yii::$app->user->id;        
        $query = UserDiagnosis::find()->where(['patient_id'=>$patient_id]);
        $model = $query->groupBy('diagnosis')->orderBy('created_at')->all();
        
        if ($model) {
            $monthes = UserDiagnosis::find()
                ->select(['FROM_UNIXTIME(created_at, "%m.%Y") AS month'])
                ->where(['patient_id'=>$patient_id])
                ->groupBy('month')
                ->orderBy('created_at')
                ->asArray()
                ->all();
            
            foreach ($model as $k=>$value) {
                $diagnosis = $value->diagnosis;
                $mkb = trim(explode(' ', $diagnosis)[0], '.');
                
                if ($monthes) {
                    foreach ($monthes as $i=>$month) {
                        $month = $month['month'];
                        $count = UserDiagnosis::getDiagnosisCount($diagnosis, $patient_id, $month);                        
                        $result['labels'][$i] = $month;
                        $result['datasets'][$k]['label'] = $diagnosis;
                        $result['datasets'][$k]['labelShort'] = $mkb;
                        $result['datasets'][$k]['data'][$i] = $count;
                        $result['datasets'][$k]['backgroundColor'][$i] = AppHelper::generateHex($k);
                        
                    }            
                    $result['monthCount'] = count($monthes);
                }
            }            
        }
            
        return $result;
    }       
}