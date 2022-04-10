<?php
// Раздел "Интрепретация результатов анализов"

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\forms\InterpForm;
use app\helpers\AppHelper;
use app\models\CommonUser;
use app\models\research\ResearchIndex;
use app\models\research\ResearchType;
use app\models\research\ResearchUnit;
use app\models\user\UserAnalysis;

class InterpController extends Controller
{        
    public function beforeAction($action)
    {
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
    
    public function actionIndex()
    {
        $model = ResearchType::find()->where(['status'=>10])->orderBy('name')->all();
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }
    
    public function actionGetNorms()
    {
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            Yii::$app->response->format = 'json';
            
            $result = [];
            $model = ResearchIndex::find()->where(['type_id'=>$post['type_id'], 'status'=>10])->all();
            
            if ($model) {
                foreach ($model as $value) {
                    $result[$value->id] = ResearchIndex::getNorms($value->id);
                }
            }
            
            return $result;
        }
    }
    
    public function actionFilter($type_id, $query = null)
    {
        if ($query) {
            Yii::$app->response->format = 'json';
            
            $model = ResearchIndex::find()
                ->select(['id', 'CONCAT(name, " ", name_alt) AS text'])
                ->where(['type_id'=>$type_id, 'status'=>10])
                ->andWhere(['OR', 
                    ['like', 'name', $query],
                    ['like', 'name_alt', $query]
                ])
                ->orderBy('name')
                ->limit(30)
                ->asArray()
                ->all();
            $out = ['results'=>['id'=>'', 'text'=>'']];
            $out['results'] = array_values($model);
            
            return $out;
        }
        
        return $this->renderAjax('_ajax/filter', [
            'type_id'=>$type_id
        ]);
    }        

    public function actionForm($id)
    {                
        $type = $this->findModel($id);
        $indexes = ResearchIndex::find()->where(['IS', 'parent_id', null])->andWhere(['type_id'=>$type->id, 'status'=>10])->orderBy('name')->all();
        $user = Yii::$app->user;
        
        $model = new InterpForm([
            'user_birthday'=>($user->isGuest) ? null : $user->identity->user_birth,
            'user_fullname'=>($user->isGuest) ? null : $user->identity->fullname,
            'user_sex'=>($user->isGuest) ? 1 : $user->identity->sex
        ]);
        
        return $this->render('_form', [
            'model'=>$model,
            'indexes'=>$indexes,
            'type'=>$type,
            'user'=>$user
        ]);
    }
    
    public function actionInterpMany()
    {                
        $post = Yii::$app->request->post();
        $user = Yii::$app->user;
        
        if (Yii::$app->request->isAjax && $post) {
            Yii::$app->response->format = 'json';
            
            if ($post['user_birthday'] === '') {                
                return [ 'actions'=>null, 'content'=>'Не заполнено поле "Дата рождения".', 'success'=>false, 'type'=>'dark' ];
            } elseif ($post['research_date'] === '') {
                return [ 'actions'=>null, 'content'=>'Не заполнено поле "Дата исследования".', 'success'=>false, 'type'=>'dark' ];
            } elseif (empty($post['values'])) {
                return [ 'actions'=>null, 'content'=>'Не заполнено ни одно поле "Значение".', 'success'=>false, 'type'=>'dark' ];
            } else {
                $age = AppHelper::calculateAge(date('d.m.Y', strtotime($post['user_birthday'])));
                $sex = $post['user_sex'];
                $content = $this->renderAjax('_ajax/many', [
                    'age'=>$age,
                    'sex'=>$sex,
                    'values'=>$post['values']
                ]);
                
                return [ 'actions'=>['save'=>!$user->isGuest, 'print'=>true], 'content'=>$content, 'success'=>true, 'type'=>'dark' ];
            }
        }    
    }
    
    public function actionInterpOne()
    {                
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            Yii::$app->response->format = 'json';
            
            if ($post['user_birthday'] === '') {                
                return [ 'actions'=>null, 'content'=>'Не заполнено поле "Дата рождения".', 'success'=>false, 'type'=>'dark' ];
            } elseif ($post['research_date'] === '') {
                return [ 'actions'=>null, 'content'=>'Не заполнено поле "Дата исследования".', 'success'=>false, 'type'=>'dark' ];
            } elseif ($post['value'] === '') {
                return [ 'actions'=>null, 'content'=>'Не заполнено поле "Значение".', 'success'=>false, 'type'=>'dark' ];
            } else {
                $age = AppHelper::calculateAge(date('d.m.Y', strtotime($post['user_birthday'])));
                $sex = $post['user_sex'];

                return ResearchIndex::getInterpretation($post['index_id'], $post['value'], $post['unit_id'], $sex, $age);
            }
        }        
    }
    
    public function actionSave()
    {
        Yii::$app->response->format = 'json';
        
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            
            if ($post['multiple'] === 'true') {
                foreach ($post['values'] as $value) {
                    $model = new UserAnalysis([
                        'type_id'=>$post['type_id'],
                        'index_id'=>$value['index_id'],
                        'unit_id'=>$value['unit_id'],
                        'value'=>$value['value'],
                        'user_id'=>Yii::$app->user->id,
                        'created_at'=>date('U', strtotime($post['research_date']))
                    ]);
                    
                    if (!$model->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }                
            } else {                
                $model = new UserAnalysis([
                    'type_id'=>$post['type_id'],
                    'index_id'=>$post['index_id'],
                    'unit_id'=>$post['unit_id'],
                    'value'=>$post['value'],
                    'user_id'=>Yii::$app->user->id,
                    'created_at'=>date('U', strtotime($post['research_date']))
                ]);
                
                if (!$model->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }  
            
            $transaction->commit();
            return true;
        }
        
        return false;
    }   
    
    public function actionPrint($json)
    {
        $data = json_decode($json);
        
        return $this->renderAjax('print', [
            'data'=>$data
        ]);
    }        
    
    protected function findModel($id)
    {
        if (($model = ResearchType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function getInterpetation($model)
    {
        $result = [];        
        $values = $model->values;
        
        if ($values) {
            $age = AppHelper::calculateAge($model->user_birthday);
            $sex = $model->user_sex;
            
            foreach ($values as $key=>$value) {
                if ($value['value'] == '') { continue; }
                
                $index = ResearchIndex::findOne($value['index_id']);
                $interp = ResearchIndex::getInterpretation($value['index_id'], $value['value'], $value['unit_id'], $sex, $age);
                $norms = ResearchIndex::getNorms($value['index_id'], $sex);
                $unit = ResearchUnit::findOne($value['unit_id']);
                
                $result[$key]['index_id'] = $value['index_id'];
                $result[$key]['index_name'] = $index->name;
                $result[$key]['value'] = $value['value'];
                $result[$key]['norms'] = $norms;
                $result[$key]['unit_id'] = $value['unit_id'];
                $result[$key]['unit_name'] = $unit->name;
                $result[$key]['content'] = $interp['content'];
                $result[$key]['type'] = $interp['type'];                
            }
        }
        
        return $result;
    }      
}