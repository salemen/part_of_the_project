<?php
namespace app\modules\med\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use app\models\cardio\Cardio;
use app\models\cardio\CardioResult;
use app\models\cron\CronNotification;

class CardioController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'close-job'=>['POST'],
                    'take-job'=>['POST']
                ]
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $dataProvider = new ActiveDataProvider([
            'query'=>Cardio::find()->where(['is_payd'=>true])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionResult($id)
    {
        if (!Yii::$app->user->can('permCardio')) {
            Yii::$app->session->setFlash('cardioForbidder', ['title'=>'Внимание!', 'content'=>'У вас нет доступа к функционалу "Расшифровка ЭКГ".', 'type'=>'orange']);
            return $this->redirect(['view', 'id'=>$id]);
        }
        
        $docs = $this->findModel($id)->cardioDocs; 
        $model = (CardioResult::findOne(['cardio_id'=>$id])) ? : new CardioResult(['cardio_id'=>$id]);        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$id]);   
        }
        
        return $this->render('result', [
            'docs'=>$docs,
            'model'=>$model
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id); 
        
        return $this->render('view', [
            'model'=>$model
        ]);
    }      
    
    public function actionViewResult($id)
    {
        $model = CardioResult::findOne(['cardio_id'=>$id]);
        
        return $this->render('view-result', [
            'model'=>$model
        ]);
    }

    public function actionCloseJob($id)
    {
        if (!Yii::$app->user->can('permCardio')) {
            Yii::$app->session->setFlash('cardioForbidder', ['title'=>'Внимание!', 'content'=>'У вас нет доступа к функционалу "Расшифровка ЭКГ".', 'type'=>'orange']);
            return $this->redirect(['view', 'id'=>$id]);
        }
        
        $model = $this->findModel($id);
        
        if ($model->cardioResult) {
            $model->updateAttributes([
                'is_end'=>true
            ]);   
            $this->saveNotification($model);
        } else {
            Yii::$app->session->setFlash('cardioIsUsed', ['title'=>'Внимание!', 'content'=>'Отсутствует результат "Расшифровка ЭКГ".', 'type'=>'orange']);
        }
        
        return $this->redirect(['view', 'id'=>$id]);
    }
    
    public function actionTakeJob($id)
    {
        if (!Yii::$app->user->can('permCardio')) {
            Yii::$app->session->setFlash('cardioForbidder', ['title'=>'Внимание!', 'content'=>'У вас нет доступа к функционалу "Расшифровка ЭКГ".', 'type'=>'orange']);
            return $this->redirect(['view', 'id'=>$id]);
        }
        
        $model = $this->findModel($id);
        
        if ($model->employee_id == null) {
            $model->updateAttributes([
                'employee_id'=>Yii::$app->user->id
            ]);                
        } else {
            Yii::$app->session->setFlash('cardioIsUsed', ['title'=>'Внимание!', 'content'=>'У заявки уже есть исполнитель.', 'type'=>'orange']);
        }        
        
        return $this->redirect(['view', 'id'=>$id]);
    }        
    
    protected function findModel($id)
    {
        if (($model = Cardio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    protected function saveNotification($cardio)
    {
        $message = "<b>Расшифровка ЭКГ Онлайн выполнена! </b><br>Результат расшифровки ЭКГ находится в вашем личном кабинете.";
        
        $model = new CronNotification();
        $model->target = (string)$cardio->id;
        $model->message = base64_encode($message);
        $model->type = CronNotification::TYPE_CARDIO_SUCCESS;
        
        if ($model->save()) {
            return true;
        }
        
        throw new ServerErrorHttpException('Не удалось сформировать сообщение.');        
    }
}
