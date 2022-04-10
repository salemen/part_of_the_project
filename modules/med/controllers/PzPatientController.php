<?php
namespace app\modules\med\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use app\models\pz\Patient;

class PzPatientController extends Controller
{
    public function actionCreate()
    {        
        $model = new Patient(['u_visible'=>1]);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';

            if ($model->save()) {
                return [
                    'id'=>$model->u_id,
                    'isNew'=>true,
                    'text'=>implode(' ', [$model->u_fam, $model->u_ima, $model->u_otc, "({$model->u_data_ros})"])
                ];
            } else {
                return ActiveForm::validate($model);
            }
        }
        
        return $this->renderAjax('_form1', [
            'model'=>$model
        ]);
    }
    
    public function actionUpdate($id)
    {        
        $model = $this->findModel($id);
        $model->u_data_ros = date('d.m.Y', strtotime($model->u_data_ros));
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            
            if ($model->save()) {
                return [
                    'id'=>$model->u_id,
                    'isNew'=>false,
                    'text'=>implode(' ', [$model->u_fam, $model->u_ima, $model->u_otc, "({$model->u_data_ros})"])                            
                ];
            } else {
                return ActiveForm::validate($model);
            }
        }
        
        return $this->renderAjax('_form', [
            'model'=>$model
        ]);?>

   <?php }

    public function actionFind($id = null, $query = null)
    {
        Yii::$app->response->format = 'json';
        
        $out = ['results'=>[
            'id'=>'',
            'text'=>''
        ]];

        if ($query) {
            $data = Patient::find()
                ->select(['u_id AS id', 'CONCAT(u_fam, " ", u_ima, " ", u_otc, " (", u_data_ros, ")") AS text'])
                ->where(['OR',
                    ['like', 'u_fam', $query],
                    ['like', 'u_ima', $query],
                    ['like', 'u_otc', $query],
                    ['like', 'CONCAT(u_fam, " ", u_ima, " ", u_otc)', $query]
                ])
                ->orderBy([
                    'u_fam'=>SORT_ASC,
                    'u_ima'=>SORT_ASC,
                    'u_otc'=>SORT_ASC
                ])
                ->limit(30)
                ->asArray()
                ->all();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $model = Patient::findOne($id);
            $out['results'] = [
                'id'=>$id,
                'text'=>implode(' ', [$model->u_fam, $model->u_ima, $model->u_otc, "({$model->u_data_ros})"])
            ];
        }
        
        return $out;
    }
    
    protected function findModel($id)
    {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}