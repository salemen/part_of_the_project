<?php
// Раздел "Вакцинация"
// TODO Не работает

namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\vaccine\VacAge;
use app\models\vaccine\VacRelation;
use app\models\vaccine\search\VacAge as VacAgeSearch;
use app\models\vaccine\search\VacAgeRelation as VacAgeRelationSearch;

class VaccineController extends Controller
{
    public function actionIndex()
    {       
        $model = new DynamicModel(['fullname', 'user_birth', 'city', 'sicks']);
        $model->addRule(['fullname'], 'required', ['message'=>'Необходимо заполнить «ФИО».'])
            ->addRule(['user_birth'], 'required', ['message'=>'Необходимо заполнить «Дата рождения».'])
            ->addRule(['city'], 'required', ['message'=>'Необходимо заполнить «Город».']);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $age = AppHelper::calculateMedicalAge($model->user_birth);
            $age_id = VacAge::findOne(['name'=>$age]);
            
            return $this->redirect(['sicks', 'age_id'=>$age_id]);
        }
        
        return $this->render('index', [
            'model'=>$model
        ]);
    } 
    
    public function actionSicks()
    {        
        $searchModel = new VacAgeRelationSearch();
        $dataProvider = $searchModel->searchByAge(Yii::$app->request->queryParams);
        $dataProvider->query->groupBy('sick_id');

        return $this->render('sicks', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionVaccines($sick_id)
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>VacRelation::find()->where(['sick_id'=>$sick_id]),
            'pagination'=>false,
            'sort'=>false
        ]);

        return $this->renderAjax('vaccines', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionYear()
    {        
        $searchModel = new VacAgeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('year', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }                   
}