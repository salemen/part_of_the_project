<?php
// Заявки от пациентов (вызов врача на дом и т.д.)

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\forms\proposal\CallDoctorForm;
use app\models\user\UserProposal;
use app\models\oms\Oms;
use app\models\geo\GeoCity;

class ProposalController extends Controller
{    
    public function actionIndex($id)
    {

        switch ($id) {
            case UserProposal::TYPE_CALL_DOCTOR:
                $model = new CallDoctorForm();
                $title = 'Вызов врача на дом';
                $view = 'call-doctor';
                break;
            default:
                throw new NotFoundHttpException('Страница не найдена');
        }

        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = new UserProposal();

            $body = 'Новая заявка на вызов врача на сайте 0323.ru <br>';
            $body .= Html::a('Перейти в заявки', Url::to(['/med/proposal'], true));
            $body2 = 'Вы оставили заявку на вызов врача на дом <br>';
            $mod = Yii::$app->request->post();
            $city = $mod['CallDoctorForm']['city'];
            $mail = $mod['CallDoctorForm']['email'];

            if (!empty($mail)){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo($mail)
                    ->setSubject('Онлайн-Поликлиника 0323.ru: Вызов врача на дом')
                    ->setHtmlBody($body2)
                    ->send();
            }

            if ($city == 322 || $city == 'Томск'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo(array('leo@0370.ru','pab@0370.ru'))
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }elseif ($city == 92 || $city == 'Краснодар'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo('callkrd@0370.ru')
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }elseif ($city == 328 || $city == 'Кызыл'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo('shmsh@0370.ru')
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }elseif ($city == 102 || $city == 'Геленджик'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo('sg@0370.ru')
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }elseif ($city == 234 || $city == 'Новосибирск'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo('santal@0354.ru')
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }elseif ($city == 70 || $city == 'Калуга'){
                Yii::$app->mailer->compose()
                    ->setFrom('no-reply@santal-online.ru')
                    ->setTo(array('leo@0370.ru','pab@0370.ru'))
                    ->setSubject('Онлайн-Поликлиника: Вызов врача на дом')
                    ->setHtmlBody($body)
                    ->send();
            }


            Yii::$app->session->setFlash('proposalSuccess', [
                'title'=>'Внимание!',
                'content'=>'Ваша заявка зарегистрирована. Мы свяжемся с Вами в ближайшее время.',
                'type'=>'green'
            ]);
            return $this->goHome();
        }

        $city = GeoCity::find()->where(['IN', 'id', [322, 92, 84, 328, 102, 234, 70]])->all();
        $items = ArrayHelper::map($city, 'id', 'name');


        $dep = Oms::find()->all();
        $items2 = ArrayHelper::map($dep, 'oms', 'oms');


        return $this->render('index', [
            'model'=>$model,
            'title'=>$title,
            'view'=>$view,
            'items'=>$items,
            'items2'=>$items2,
        ]);
    }   
}