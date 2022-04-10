<?php
namespace app\modules\b2b\controllers;

use Yii;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use yii\web\Controller;
use app\modules\b2b\forms\DirectorSignupForm as SignupForm;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'fileapi-upload'=>[
                'class'=>FileAPIUpload::className(),
                'path'=>'@storage/temp',
                'uploadOnlyImage'=>false
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSignup()
    {
        $user = Yii::$app->user;
        if (!$user->isGuest) {
            $roles = $user->identity->roles;
            return ($roles->is_director || $roles->is_visor) ? $this->goHome() : $this->redirect(['/site/index']);
        }

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['/b2b/organization']);
        }

        $this->layout = '@app/views/layouts/form';
        return $this->render('signup', [
            'model'=>$model
        ]);
    }
}