<?php
namespace app\modules\med\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use app\helpers\AppHelper;
use app\models\monitor\MonitorPassport;
use app\models\monitor\MonitorProtocolOrvi;
use app\models\monitor\search\MonitorPassport as MonitorPassportSearch;
use app\models\oms\Oms;
use app\models\proposal\ProposalCallDoctor;
use app\models\user\UserProposal;
use app\forms\proposal\CallDoctorForm;
use app\models\employee\Employee;
use app\models\data\Department;
use app\models\patient\Patient;
use app\models\user\UserData;

class MonitorController extends Controller
{
    public function actionIndex()
    {

        $time = strtotime('-14 days');
        $date = date('U', $time);
        $searchModel = new MonitorPassportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['is_end'=>false])
            ->andWhere(['is_archive'=>false])
            ->andWhere(['>', 'monitor_passport.created_at', $date])
            ->orderBy([
                'protocol_status'=>SORT_DESC,
                'passport_status'=>SORT_DESC,
                'updated_at'=>SORT_DESC,
                'is_checked'=>false
            ]);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionIndexArchive()
    {
        $time = strtotime('-60 days');
        $date = date('U', $time);
        $searchModel = new MonitorPassportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['is_end'=>true])
            ->andWhere(['OR', ['is_archive'=>true], ['>', 'monitor_passport.created_at', $date]])
            ->orderBy([
                'protocol_status'=>SORT_DESC,
                'passport_status'=>SORT_DESC,
                'updated_at'=>SORT_DESC,
            ]);

        return $this->render('index-archive', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model2 = new CallDoctorForm();
        $user = UserData::findOne(['user_id' => $id])? : new UserData(['user_id' => $id]);
        $user->load(Yii::$app->request->post()) && $user->save();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        $userProposal = ProposalCallDoctor::find()->one();

        $oms = Oms::find()->all();
        $items2 = ArrayHelper::map($oms,'oms','oms');
        $params2 = ['Выберите организацию'];

        return $this->render('_form', [
            'model2'=>$model2,
            'model'=>$model,
            'items2'=>$items2,
            'params2'=>$params2,
            'userProposal'=>$userProposal
        ]);
    }

    public function actionCall($proposal_id = null)
    {

        $call = Yii::$app->request->post();
        if(isset($call['MonitorProtocolOrvi']['p_temp'])){
            $temp = $call['MonitorProtocolOrvi']['p_temp'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_bolgorlo'])){
            $gorlo = $call['MonitorProtocolOrvi']['p_bolgorlo'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_odishka'])){
            $odishka = $call['MonitorProtocolOrvi']['p_odishka'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_zapah'])){
            $zapah = $call['MonitorProtocolOrvi']['p_zapah'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_feel'])){
            $feel = $call['MonitorProtocolOrvi']['p_feel'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_toshn'])){
            $toshn = $call['MonitorProtocolOrvi']['p_toshn'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_diarea'])){
            $diarea =  $call['MonitorProtocolOrvi']['p_diarea'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_kash'])){
            $kash = $call['MonitorProtocolOrvi']['p_kash'];
        }
        if(isset($call['MonitorProtocolOrvi']['p_kash_type'])){
            $kash_type = $call['MonitorProtocolOrvi']['p_kash_type'];
        }


        if($call){
            if(isset($call['Employee']['fullname'])) {
                $user = Employee::find()->select('id')->where(['fullname' => $call['Employee']['fullname']])->one();
                $emplo = UserData::findOne(['user_id' => $user['id']])? : new UserData(['user_id' => $user['id']]);
                $emplo->load(Yii::$app->request->post()) && $emplo->save();
                $dep = Department::find()->select('id')->where(['name' => $call['UserData']['clinic']])->one();
                $fullname1 = $call['Employee']['fullname'];
                $fullname = explode(' ', $fullname1);
                $user_f = $fullname[0];
                $user_i = $fullname[1];
                $user_o = isset($fullname[2]) ? $fullname[2] : '-';

                $model1 = new ProposalCallDoctor();
                $model1->user_f = $user_f;
                $model1->user_i = $user_i;
                $model1->user_birth = $call['Employee']['user_birth'];
                $model1->phone = $call['Employee']['phone'];
                $model1->complaint = $call['MonitorProtocolOrvi']['complain'];
                $model1->address = $call['UserData']['address'];
                $model1->created_by = $user;
                $model1->reason = "Вызов врача";
                $model1->dep_id = $dep['id'];
                $model1->save();

                $model = new UserProposal();
                $model->type_id = 10;
                $model->user_id = $user['id'];
                $model->comment = $call['MonitorProtocolOrvi']['complain'];
                $model->save();
            }
            if(isset($call['Patient']['fullname'])) {
                $user = Patient::find()->select('id')->where(['fullname' => $call['Patient']['fullname']])->one();
                $pat = UserData::findOne(['user_id' => $user['id']])? : new UserData(['user_id' => $user['id']]);
                $pat->load(Yii::$app->request->post()) && $pat->save();
                $dep = Department::find()->select('id')->where(['name' => $call['UserData']['clinic']])->one();
                $fullname1 = $call['Patient']['fullname'];
                $fullname = explode(' ', $fullname1);
                $user_f = $fullname[0];
                if($fullname[1]){
                    $user_i = $fullname[1];
                }
                $user_o = isset($fullname[2]) ? $fullname[2] : '-';

                $model1 = new ProposalCallDoctor();
                $model1->user_f = $user_f;
                if($user_i){
                    $model1->user_i = $user_i;
                }else{
                    $model1->user_i = "Аноним";
                }
                $model1->user_birth = $call['Patient']['user_birth'];
                $model1->phone = $call['Patient']['phone'];
                if(!empty($call['MonitorProtocolOrvi']['complain'])){
                    $model1->complaint = $call['MonitorProtocolOrvi']['complain'];
                }elseif(empty($call['MonitorProtocolOrvi']['complain'])){
                    $model1->complaint = '-';
                }
                $model1->address = $call['UserData']['address'];
                $model1->created_by = $user;
                $model1->reason = "Вызов врача";
                $model1->dep_id = $dep['id'];
                $model1->save();

                $model = new UserProposal();
                $model->type_id = 10;
                $model->user_id = $user['id'];
                if(!empty($call['MonitorProtocolOrvi']['complain'])){
                    $model->comment = $call['MonitorProtocolOrvi']['complain'];
                }elseif(empty($call['MonitorProtocolOrvi']['complain'])&&!empty($kash)&&!empty($kash_type)){
                    $model->comment = 'Температура-'.$temp.'. Боль в горле-'.$gorlo.'. Одышка-'.$odishka.
                        '. Чувствует запахи-'.$zapah.'. Состояние-'.$feel.'. Тошнота-'.$toshn.'. Диарея-'.$diarea.
                        '. Кашель-'.$kash.'. Тип кашля-'.$kash_type;
                }elseif(empty($call['MonitorProtocolOrvi']['complain'])&&empty($kash)&&empty($kash_type)){
                    $model->comment = 'Температура-'.$temp.'. Боль в горле-'.$gorlo.'. Одышка-'.$odishka.
                        '. Чувствует запахи-'.$zapah.'. Состояние-'.$feel.'. Тошнота-'.$toshn.'. Диарея-'.$diarea;
                }
                $model->save();
            }
            return $this->redirect(['proposal/admin', 'id'=> $model->id]);

        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['monitor/index']);
        }

        $userProposal = ProposalCallDoctor::find()->one();

        $oms = Oms::find()->all();
        $items2 = ArrayHelper::map($oms,'oms','oms');
        $params2 = ['Выберите организацию'];

        return $this->render('_form', [
            'model'=>$model,
            'items2'=>$items2,
            'params2'=>$params2,
            'userProposal'=>$userProposal
        ]);
    }

    public function actionArchive($user)
    {

        $archive = MonitorPassport::find()->where(['user_id'=>$user])->andWhere(['is_end'=>false])->one();
        $motive = Yii::$app->request->post('motive');
        if ($motive == 1){
            $motive = "Пациент умер";
        }
        if ($motive == 2){
            $motive = "Закончился срок мониторинга";
        }
        if ($motive == 3){
            $motive = "Ошибка в данных";
        }
        $archive->motive = $motive;
        $archive->is_end = 1;
        $archive->is_archive = 1;
        $archive->update();
        Yii::$app->session->setFlash('Success', [
            'title' => 'Внимание',
            'content' => 'Мониторинг пациента перенесен в архив.',
            'type' => 'green'
        ]);
        return $this->redirect(['monitor/index']);

    }

    public function actionArchiveUp($user)
    {
        $time = strtotime('-13 days');
        $created_at = date('U', $time);
        $archive = MonitorPassport::find()->where(['user_id'=>$user])->andWhere(['is_end'=>true])->one();
        $archive->is_end = 0;
        $archive->is_archive = 0;
        $archive->created_at = $created_at;
        $archive->motive = '';
        $archive->update();
        Yii::$app->session->setFlash('Success', [
            'title' => 'Внимание',
            'content' => 'Мониторинг пациента восстановлен из архива.',
            'type' => 'green'
        ]);
        return $this->redirect(['monitor/index']);

    }

    public function actionUpdateDoctor($id)
    {
        $model = $this->findModel($id);

        $model2 = MonitorProtocolOrvi::find()->where(['passport_id'=>$id])->orderBy('created_at')->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('_formdoctor', [
            'model'=>$model2
        ]);
    }
    
    public function actionView($id, $created_at = null)
    {
        $passport_id = $id;
        $model = $this->findModel($id);
        $model2 = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy('created_at')->all();        
        if ($created_at) {
            $model2 = MonitorProtocolOrvi::find()
                ->where(['passport_id'=>$passport_id])
                ->andFilterWhere(['AND', ['>=', 'created_at', $created_at], ['<', 'created_at', $created_at + 86400]])
                ->orderBy('created_at')
                ->all();
        } else {
            $model2 = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy('created_at')->all();
        }                           
        $count = count($model2);

        return $this->render('view', [
            'count'=>$count, 
            'model'=>$model,
            'model2'=>$model2,
            'data'=>$this->getStatisticData($model->id, ['p_diarea', 'p_kash', 'p_temp', 'p_chast']),
            'passport_id'=>$passport_id
        ]);
    }

    public function actionViewArchive($id, $created_at = null)
    {
        $passport_id = $id;
        $model = $this->findModel($id);
        $model2 = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy('created_at')->all();
        if ($created_at) {
            $model2 = MonitorProtocolOrvi::find()
                ->where(['passport_id'=>$passport_id])
                ->andFilterWhere(['AND', ['>=', 'created_at', $created_at], ['<', 'created_at', $created_at + 86400]])
                ->orderBy('created_at')
                ->all();
        } else {
            $model2 = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy('created_at')->all();
        }
        $count = count($model2);

        return $this->render('view-archive', [
            'count'=>$count,
            'model'=>$model,
            'model2'=>$model2,
            'data'=>$this->getStatisticData($model->id, ['p_diarea', 'p_kash', 'p_temp', 'p_chast']),
            'passport_id'=>$passport_id
        ]);
    }
    
    public function actionCheck($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['is_checked'=>true, 'checked_at'=>date('U')]);
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionToDoc($id)
    {
        $model = $this->findModel($id);
        $model->updateAttributes(['is_to_doc'=>true]);
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index-archive']);
    }
    
    protected function findModel($id)
    {
        if (($model = MonitorPassport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
    
    protected function getStatisticData($passport_id, $attributes)
    {
        $result = ['labels'=>[], 'datasets'=>[['data'=>[], 'label'=>'']]];        
        $model = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy('created_at')->all();
        
        if ($model) {
            foreach ($attributes as $keyAttr=>$attr) {    
                foreach ($model as $keyModel=>$value) {
                    $result['labels'][$keyModel] = date('d.m.Y', $value->created_at);
                    $result['datasets'][$keyAttr]['data'][$keyModel] = $value->{$attr};                    
                }      
                
                $result['datasets'][$keyAttr]['label'] = MonitorProtocolOrvi::instance()->getAttributeLabel($attr);
                $result['datasets'][$keyAttr]['borderColor'] = AppHelper::generateHex($keyAttr);
                $result['datasets'][$keyAttr]['fill'] = false;
            }
        } else {
            return null;
        }
        
        return json_encode($result);
    }        
}