<?php
namespace app\modules\covid\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\CommonUser;
use app\modules\covid\forms\DiaryForm;
use app\modules\covid\models\CovidDiary;
use app\modules\covid\models\CovidDiaryCheck;

class DiaryController extends Controller
{    
    public $layout = '@app/views/layouts/main';
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                        
                        'roles'=>['@']
                    ]
                ],
                'denyCallback'=>function($rule, $action) {
                    Yii::$app->user->loginUrl = array_merge(Yii::$app->user->loginUrl, ['redirect'=>Yii::$app->request->url]);
                    Yii::$app->response->redirect(Yii::$app->user->loginUrl);         
                }
            ]
        ];
    }
    
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
        $model = $this->getDiaries();
        $dataProvider = ($model) ? new ActiveDataProvider([
            'query'=>CovidDiaryCheck::find()->where(['diary_id'=>$model->id]),
            'pagination'=>false,
            'sort'=>false
        ]) : null;
        $user = Yii::$app->user->identity;
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'model'=>$model,            
            'user'=>$user
        ]);
    }
    
    public function actionForm($id)
    {
        $model = new DiaryForm(['diary_id'=>$id]);
        $check = CovidDiaryCheck::find()->where(['diary_id'=>$id])->orderBy(['created_at'=>SORT_DESC])->one();
        
        if ($check && date('d.m.Y', $check->created_at) === date('d.m.Y')) {
            Yii::$app->session->setFlash('todayCheckExists', ['title'=>'Внимание!', 'content'=>'Сегодня вы уже заполняли дневник вакцинации', 'type'=>'orange']);
            return $this->redirect(['index']);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
        return $this->render('_form', [
            'model'=>$model
        ]);
    }
    
    protected function getDiaries()
    {
        $odataClient = Yii::$app->odata->connect();
        $guid = Yii::$app->user->identity->guid ? : '-';
        
        $values = $odataClient
            ->{"InformationRegister_ЦСМ_ЖурналВакцинации"}
            ->filter("Пациент_Key eq guid'{$guid}'")
            ->get()
            ->values();
            
        if ($values) {
            foreach ($values as $value) {
                if (CovidDiary::find()->where(['usl_id'=>$value['УникальныйИдентификаторУслуги']])->exists()) {
                    continue;
                } else {
                    (new CovidDiary([
                        'usl_id'=>$value['УникальныйИдентификаторУслуги'],
                        'user_id'=>$guid,
                        'vac_org_1'=>'Название организации не указано',
                        'vac_name_1'=>$value['НазваниеПрепарата'] ? : 'Название вакцины не указано',
                        'vac_date_1'=>date('d.m.Y', strtotime($value['ДатаВыполнения']))
                    ]))->save();
                }
            }
        }
        
        return CovidDiary::findOne(['user_id'=>$guid]);
    }
}