<?php
// Раздел "Наблюдение онлайн"

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\AppHelper;
use app\models\monitor\MonitorPassport;
use app\models\monitor\MonitorProtocolOrvi;
use app\models\patient\Patient;
use app\models\user\UserData;
use app\models\oms\Oms;
use app\models\data\Department;

class MonitorController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index'],
                        'allow'=>true,
                        'roles'=>['?']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/monitor_protocol.php';
        return $this->render('index');
    }

    public function actionPassport($type)
    {
        $this->layout = '@app/views/layouts/monitor_protocol.php';
        $user = Yii::$app->user->identity;
        $passExists = MonitorPassport::find()->where(['protocol_type'=>$type, 'is_end'=>false, 'user_id'=>$user->id])->exists();
        $user_data = UserData::findOne(['user_id'=>$user->id]) ?  : new UserData(['user_id'=>$user->id]);
        if ($passExists) {
            return $this->redirect(['protocol', 'type'=>$type]);
        }
        $model = new MonitorPassport([
            'protocol_type'=>$type,
            'user_id'=>$user->id
        ]);

        $model->scenario = 'passport-create';
        if ($user->fullname !== 'Аноним') {
            $fullname = explode(' ', $user->fullname);
            $model->user_f = $fullname[0];
            $model->user_i = $fullname[1];
            $model->user_o = $fullname[2];
        }

        $model->user_birth = $user->user_birth;
        $model->city = $user->city;
        $model->clinic = isset($user->data) ? $user->data->clinic : null;
        $model->address = isset($user->data) ? $user->data->address : null;
        $model->period_start = ($model->period_start) ? : date('d.m.Y');
        $model->period_end = ($model->period_end) ? : date('d.m.Y', strtotime("+2 week"));

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            if ($model->sicks) { $model->sicks = implode(', ', $model->sicks); }
            if ($model->save()) {
                if ($user instanceof Patient) {
                    $fullname = mb_convert_case(trim($model->user_f) . ' ' . trim($model->user_i) . ' ' . trim($model->user_o), MB_CASE_TITLE, 'utf-8');
                    $user->updateAttributes([
                        'fullname'=>$fullname,
                        'user_birth'=>$model->user_birth,
                        'city'=>mb_convert_case(trim($model->city), MB_CASE_TITLE, 'utf-8')
                    ]);
                }
                $this->saveUserData($model, $user);
                $this->setPassportStatus($model);

                return $this->redirect(['protocol', 'type'=>$type]);
            } else {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }
        }

        $dep = Department::find()->where(['between', 'id', '1', '24'])->all();
        $items = ArrayHelper::map($dep,'id','name');
        $params = ['Выберите поликлинику'];

        $oms = Oms::find()->all();
        $items2 = ArrayHelper::map($oms,'oms','oms');
        $params2 = ['Выберите организацию'];

        return $this->render('_form/passport', [
            'model'=>$model,
            'items' => $items,
            'items2'=>$items2,
            'params'=>$params,
            'params2'=>$params2,
            'user_data'=>$user_data,
        ]);
    }

    public function actionProtocol($type)
    {
        $this->layout = '@app/views/layouts/monitor_protocol.php';
        $user_id = Yii::$app->user->id;
        $passport_id = MonitorPassport::find()->select('id')
            ->where(['protocol_type'=>$type, 'is_end'=>false, 'user_id'=>$user_id])
            ->orderBy(['created_at'=>SORT_DESC])
            ->scalar();

        $last = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy(['created_at'=>SORT_DESC])->one();
        if ($last && ($last->created_at > date('U') - 7200)) {
            Yii::$app->session->setFlash('protocolForbidden', ['title'=>'Внимание!', 'content'=>'Целесообразно повторить оценку состояния минимум через два часа после прошлого измерения.', 'type'=>'orange']);
            return $this->redirect(['view', 'type'=>$type]);
        }

        if ($passport_id) {
            $model = new MonitorProtocolOrvi(['passport_id'=>$passport_id]);

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    $this->setSicknessStatus($model);
                    return $this->redirect(['view', 'type'=>$type]);
                } else {
                    Yii::$app->response->format = 'json';
                    return ActiveForm::validate($model);
                }
            }

            return $this->render('_form/protocol', [
                'model'=>$model,
                'type'=>$type
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionView($type)
    {
        $user_id = Yii::$app->user->id;
        $passport_id = MonitorPassport::find()->select('id')
            ->where(['protocol_type'=>$type, 'is_end'=>false, 'user_id'=>$user_id])
            ->orderBy(['created_at'=>SORT_DESC])
            ->scalar();
        $lastIds = MonitorProtocolOrvi::find()
            ->where(['passport_id'=>$passport_id])
            ->orderBy(['created_at'=>SORT_DESC])
            ->limit(3)
            ->asArray()
            ->all();
        $model = MonitorProtocolOrvi::find()->where(['IN', 'id', $lastIds])->orderBy('created_at')->all();

        if ($model) {
            $count = count($model);
            $modelLast = MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy(['created_at'=>SORT_DESC])->one();

            return $this->render('view', [
                'count'=>$count,
                'model'=>$model,
                'modelLast'=>$modelLast,
                'data'=>$this->getStatisticData($passport_id, ['p_diarea', 'p_kash', 'p_temp', 'p_chast']),
                'type'=>$type
            ]);
        }

        return $this->redirect(['index']);
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

    protected function saveUserData($data, $user)
    {
        $model = (isset($user->data)) ? UserData::findOne(['user_id'=>$user->id]) : new UserData();
        $model->user_id = $user->id;
        $model->address = mb_convert_case(trim($data->address), MB_CASE_TITLE, 'utf-8');
        $model->clinic = trim($data->clinic);
        $model->save();
    }

    protected function setPassportStatus($model)
    {
        $result = 0;

        $reason = (int)$model->reason;
        if ($reason !== 10) {
            $result += 5;
        }

        $age = 0;

        if ($model->employee) {
            $age = AppHelper::calculateAge($model->employee->user_birth);
        }
        if ($model->patient) {
            $age = AppHelper::calculateAge($model->patient->user_birth);
        }

        switch ($age) {
            case 0:
            case ($age < 45):
                break;
            case ($age >= 45 && $age < 65):
                $result += 4;
                break;
            case ($age >= 65):
                $result += 5;
                break;
        }

        $sicks = $model->sicks;
        if ($sicks) {
            $parts = explode(', ', $sicks);
            $count = count($parts);
            if ($count == 1) {
                $result += 4;
            } else {
                $result += 5;
            }
        }

        $passport_status = ($reason !== 10) ? MonitorPassport::STATUS_DANGER : MonitorPassport::STATUS_SUCCESS;
        $model->updateAttributes(['passport_status'=>$passport_status, 'result'=>$result]);
    }

    protected function setSicknessStatus($model)
    {
        $result = 0;

        $temp = (double)$model->p_temp;
        switch ($temp) {
            case ($temp < 37.1):
                break;
            case ($temp >= 37.1 && $temp < 37.4):
                $result += 2;
                break;
            case ($temp >= 37.4 && $temp < 37.6):
                $result += 4;
                break;
            case ($temp >= 37.6):
                $result += 5;
                break;
        }

        if ($model->p_pulsmetr) {
            $pulsmetr = (int)$model->p_pulsmetr;
            switch ($pulsmetr) {
                case 0:
                    break;
                case ($pulsmetr < 95):
                    $result += 5;
                    break;
            }
        }

        if ($model->p_kash) {
            $kash = (int)$model->p_kash;
            switch ($kash) {
                case '':
                case ($kash < 10):
                    break;
                case ($kash >= 10 && $kash < 15):
                    $result += 2;
                    break;
                case ($kash >= 15):
                    $result += 5;
                    break;
            }

            if ($kash >= 10) {
                switch ($model->p_kash_type) {
                    case null:
                        break;
                    case 'влажный':
                        break;
                    case 'сухой':
                        $result += 5;
                        break;
                }
            }
        }

        $chast = (int)$model->p_chast;
        switch ($chast) {
            case ($chast < 20):
                break;
            case ($chast >= 20 && $chast < 25):
                $result += 1;
                break;
            case ($chast >= 25 && $chast < 30):
                $result += 4;
                break;
            case ($chast >= 30):
                $result += 5;
                break;
        }

        switch ($model->p_tyazh) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 2;
                break;
            case 'выраженное':
                $result += 5;
                break;
        }

        switch ($model->p_bolmysh) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 1;
                break;
            case 'выраженное':
                $result += 2;
                break;
        }

        switch ($model->p_bolgorlo) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 2;
                break;
            case 'выраженное':
                $result += 5;
                break;
        }

        $diarea = (int)$model->p_diarea;
        switch ($diarea) {
            case 0:
            case ($diarea < 2):
                break;
            case ($diarea == 2):
                $result += 1;
                break;
            case ($diarea == 3):
                $result += 2;
                break;
            case ($diarea == 4):
                $result += 3;
                break;
            case ($diarea == 5):
                $result += 4;
                break;
            case ($diarea >= 6):
                $result += 5;
                break;
        }

        switch ($model->p_toshn) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 1;
                break;
            case 'выраженное':
                $result += 2;
                break;
        }

        switch ($model->p_bolgolova) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 1;
                break;
            case 'выраженное':
                $result += 2;
                break;
        }

        switch ($model->p_slab) {
            case 'отсутствует':
                break;
            case 'легкое':
                $result += 1;
                break;
            case 'выраженное':
                $result += 4;
                break;
        }

        switch ($result) {
            case 0:
            case ($result >= 0 && $result < 10):
                $protocol_status = MonitorProtocolOrvi::STATUS_SUCCESS;
                break;
            case ($result >= 10 && $result < 16):
                $protocol_status = MonitorProtocolOrvi::STATUS_WARNING;
                break;
            case ($result >= 16):
                $protocol_status = MonitorProtocolOrvi::STATUS_DANGER;
                break;
        }

        $model->updateAttributes(['result'=>$result, 'status'=>$protocol_status]);

        $passport = $model->passport;
        $passport->protocol_status = $protocol_status;
        if ($passport->passport_status == MonitorPassport::STATUS_SUCCESS && $protocol_status == MonitorProtocolOrvi::STATUS_SUCCESS) {
            $passport->is_checked = 1;
            $passport->checked_at = date('U');
        } else {
            $passport->is_checked = 0;
        }
        $passport->save();
    }
}