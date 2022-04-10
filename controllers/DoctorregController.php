<?php

namespace app\controllers;

use Yii;
use app\models\DoctorReg;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class DoctorregController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionView()
    {
       $model = new doctorreg();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

            $id = isset($_GET['id']);
            
        if(!empty($id)){
            Yii::$app->session->setFlash('success',"<center>Благодарим за регистрацию! Наш менеджер свяжется с Вами в ближайшее время.</center>");
            $tel = doctorreg::find()->select(['phone'])->where(['id' => $id])->orderBy(['id' => SORT_DESC])->all();

            Yii::$app->mailer->compose('ads', ['tel' => $tel] )->setFrom (['web-mail@0370.ru'=>'Новая регистрация врача на сайте'])-> setTo(array('web-mail@0370.ru' => 'NAME','shigo@0370.ru' => 'NAME2'))-> setSubject('Новая регистрация врача на сайте')->send ();

            return $this ->redirect('/');
        }else{
            return $this->render('view', [
            'model' => $model,
        ]);
        }
    }

    public function actionForm()
    {

        $tel = Yii::$app->request->get('tel');
        $fio = Yii::$app->request->get('fio');
        if(!empty($tel)){

            Yii::$app->mailer->compose('online', ['tel' => $tel, 'fio' => $fio])->setFrom (['web-mail@0370.ru'=>'Запрос онлайн констультации'])-> setTo('shigo@0370.ru')-> setSubject('Онлайн консультация на сайте')->send ();

            return $this ->redirect('/');
        }
    }

    protected function findModel($id)
    {
        if (($model = doctorreg::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

   
}
