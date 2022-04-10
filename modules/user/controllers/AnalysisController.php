<?php
namespace app\modules\user\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use app\models\CommonUser;
use app\models\research\ResearchIndex;
use app\models\research\ResearchNormsCol;
use app\models\research\ResearchNormsQual;
use app\models\user\UserAnalysis;
use app\models\user\UserAnalysisProposal;
use app\models\user\search\UserAnalysis as UserAnalysisSearch;

class AnalysisController extends Controller
{    
    public function beforeAction($action) {
        if (!CommonUser::isProfileValid(Yii::$app->user->id)) {
            Yii::$app->session->setFlash('passReset', [
                'title'=>'Внимание!',
                'content'=>'Пожалуйста, заполните данные учетной записи полностью.',
                'type'=>'orange'
            ]);
            
            $redirectUrl = array_merge(['/user/profile/update'], ['redirect'=>Yii::$app->request->url]);
            $this->redirect($redirectUrl);
            
            return false;
        }
        
        return parent::beforeAction($action);
    }
    
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
        $searchModel = new UserAnalysisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere([
                'user_analysis.status'=>UserAnalysis::STATUS_ACTIVE,
                'user_analysis.user_id'=>Yii::$app->user->id
            ])
            ->orderBy([
                'research_type.name'=>SORT_DESC,
                'created_at'=>SORT_DESC,
                'research_index.name'=>SORT_DESC
            ]);
        
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionCreate()
    {     
        $model = new UserAnalysis();
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {
                $model->type_id = ResearchIndex::findOne($model->index_id)->type_id;
                $model->user_id = Yii::$app->user->id;
                $model->created_at = ($model->created_at) ? date('U', strtotime($model->created_at)) : null;                
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
        $model->created_at = date('d.m.Y', $model->created_at);
        
        if (Yii::$app->request->isAjax) {            
            if ($model->load(Yii::$app->request->post())) {
                $model->type_id = ResearchIndex::findOne($model->index_id)->type_id;
                $model->user_id = Yii::$app->user->id;
                $model->created_at = ($model->created_at) ? date('U', strtotime($model->created_at)) : null;                
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
        $model = $this->findModel($id);
        
        if ($model->is_lab) {
            $model->updateAttributes(['status'=>UserAnalysis::STATUS_INACTIVE]);
        } else {
            $model->delete();
        }
        
        return $this->redirect(['index']);
    }       
    
    public function actionChart($type_id)
    {
        $user = Yii::$app->user->identity;
        $model = ArrayHelper::map(
            UserAnalysis::find()
                ->joinWith(['researchIndex'])
                ->where([
                    'user_analysis.status'=>10,
                    'user_analysis.type_id'=>$type_id,
                    'grade_id'=>ResearchIndex::GRADE_COL,
                    'user_analysis.user_id'=>$user->id
                ])
                ->orderBy('research_index.name')
                ->all(), 'index_id', function ($item) {
                    return $item->researchIndex->name;
                });
        
        return $this->render('chart', [
            'model'=>$model,
            'type_id'=>$type_id,
            'user'=>$user
        ]);
    }
    
    public function actionLoadChart()
    {
        $request = Yii::$app->request;
        
        if ($request->isAjax) {            
            $type_id = $request->post('type_id');
            $index_id = $request->post('index_id');
            $user_sex = $request->post('user_sex');
            $user_id = Yii::$app->user->id;

            if ($type_id == null || $index_id == null) {
                throw new NotFoundHttpException('Параметр не найден, попробуйте позже');
            }
            
            $data = [];
            $norms = [];
            
            $model = UserAnalysis::find()
                ->where(['type_id'=>$type_id, 'index_id'=>$index_id, 'user_id'=>$user_id])
                ->orderBy('created_at')
                ->all();

            if ($model) {
                foreach ($model as $key=>$value) {
                    $data['labels'][$key] = date('d.m.Y', $value->created_at);
                    $data['datasets'][0]['borderColor'] = AppHelper::generateHex(0);
                    $data['datasets'][0]['data'][$key] = $value->value;
                    $data['datasets'][0]['fill'] = false;
                    $data['datasets'][0]['label'] = $value->researchIndex->name;
                }
                
                $norms = $this->getNorms($index_id, $user_sex);
            }
            
            Yii::$app->response->format = 'json';

            return [
                'count'=>count($model),
                'data'=>$data,
                'norms'=>$norms
            ];
        }
    }
    
    public function actionExportLab()
    {
        $model = new UserAnalysisProposal();
        
        $user = Yii::$app->user->identity;
        
        if ($user) {
            if ($user->fullname && $user->fullname !== 'Аноним') {
                $fio = AppHelper::getFullNameAsArray($user->fullname);
                $model->user_f = $fio['f'];
                $model->user_i = $fio['i'];
                $model->user_o = $fio['o'];
            }

            if ($user->sex) {
                $model->user_sex = ($user->sex === 0) ? 'Ж' : 'М';
            }

            if ($user->user_birth) {
                $time = strtotime($user->user_birth);
                $model->user_year = date('Y', $time); 
            }
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) { 
                Yii::$app->session->setFlash('passReset', [
                    'title'=>'Внимание!',
                    'content'=>'Заявка успешно сохранена и будет обработана в течение 2-х часов.<br> Вы можете продолжить дальнейшее использование сайта.',
                    'type'=>'green'
                ]);
                
                return $this->redirect(['index']);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }
        }
        
        return $this->renderAjax('_form-export-lab', [
            'model'=>$model
        ]);
    }     
    
    public function actionPopulateFilter()
    {
        Yii::$app->response->format = 'json';
        
        $data = [['id'=>'', 'text'=>'']];
        $index_id = Yii::$app->request->post('index_id');
        $index = ResearchIndex::findOne($index_id);
        
        if ($index) {
            if ($index->grade_id === 0) {
                $model = ResearchNormsCol::findAll(['index_id'=>$index_id]);
            } elseif ($index->grade_id === 10) {
                $model = ResearchNormsQual::findAll(['index_id'=>$index_id]);
            } else {
                $model = false;
            }
        }

        if ($model) {
            foreach ($model as $option) {
                $data[] = ['id'=>$option->unit_id, 'text'=>$option->unit->name];
            }
        }
        
        return ['data'=>$data];
    }   
    
    protected function findModel($id)
    {
        if (($model = UserAnalysis::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function getNorms($index_id, $sex)
    {        
        $model = ResearchIndex::getNorms($index_id);   
        $result = [];
        $values = [];
        
        if ($model) {
            $norms = explode(' - ', $model[0]['norms'][$sex]);
            $min_value = $norms[0];
            $max_value = $norms[1];
            $values = [$min_value, $max_value];
        }
        
        if ($values) {
            foreach ($values as $key=>$value) {
                array_push($result, $this->makeAnnotation($value, $key));
            }
        }

        return $result;
    }   
    
    protected function makeAnnotation($value, $key)
    {
        $content = ($key === 0) ? 'Норма, min' : 'Норма, max';
        
        $result = [
            'borderColor'=>'#ce7d78',
            'borderDash'=>[5, 5],
            'borderWidth'=>2,
            'label'=>[
                'backgroundColor'=>'#ce7d78',
                'content'=>$content,
                'enabled'=>true,
                'fontSize'=>8,
                'position'=>'right',
                'xPadding'=>4,
                'yPadding'=>4
            ],
            'mode'=>'horizontal',
            'scaleID'=>'y-axis-0',
            'type'=>'line',            
            'value'=>(float)$value
        ];
        
        return $result;
    }
}