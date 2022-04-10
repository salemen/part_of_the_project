<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\helpers\AppHelper;
use app\models\employee\Employee;
use app\models\test\Test;
use app\models\test\TestUserSession;
use app\models\zung\ZungAnswers;

class TestController extends Controller
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
    
    public function actionIndex($test_id)
    {        
        $user = Yii::$app->user->identity;
        
        if ($user instanceof Employee) {
            $user_phone = $user->phone ? : $user->phone_work;
        } else {
            $user_phone = $user->phone;
        }
        
        $login = null;
        $query = null;
        
        if ($user_phone) {
            $login = AppHelper::localizePhone($user_phone);
        }
        
        if ($test_id == Test::ZUNG_TEST_ID) {
            $query = ZungAnswers::find()->where(['login'=>$login])->orderBy(['date_time'=>SORT_DESC]);
        } else {
            $query = TestUserSession::find()->where(['test_id'=>$test_id, 'user_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'sort'=>false
        ]);
        
        return $this->render(($test_id == Test::ZUNG_TEST_ID) ? 'zung' : 'hads', [
            'dataProvider'=>$dataProvider,
            'test_id'=>$test_id
        ]);
    }
}