<?php
// Раздел "Симптомы и болезни"

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\checker\CheckerBodyparts;
use app\models\checker\CheckerSymptoms;
use app\models\checker\search\CheckerSymptoms as CheckerSymptomsSearch;
use app\models\employee\Employee;

class SymptomController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'searchModel' => (new CheckerSymptomsSearch())
        ]);
    }

    public function actionView($id)
    {
        $doctors = null;
        $model = $this->findModel($id);
        $specialities = $model->specialities;

        if ($specialities) {
            $city = $this->getCity();
            $specArray = ArrayHelper::getColumn($specialities, 'speciality');
            if(!empty($city)) {
                $doctors = Employee::find()
                    ->joinWith(['advisor', 'documents', 'positionsActive'])
                    ->where(['IN', 'empl_pos', $specArray])
                    ->andWhere(['IS NOT', 'photo', null])
                    ->andFilterWhere(['city' => $city])
                    ->orderBy(['position' => SORT_DESC])
                    ->all();
            }elseif(empty($city)) {
                $doctors = Employee::find()
                    ->joinWith(['advisor', 'documents', 'positionsActive'])
                    ->where(['IN', 'empl_pos', $specArray])
                    ->andWhere(['IS NOT', 'photo', null])
                    ->orderBy(['position' => SORT_DESC])
                    ->all();
            }
        }

        return $this->render('view', [
            'doctors' => $doctors,
            'model' => $model
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new CheckerSymptomsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('search', [
            'searchModel' => $searchModel,
            'models' => $dataProvider->getModels(),
            'pagination' => $dataProvider->pagination
        ]);
    }

    public function actionGetBodyparts()
    {
        Yii::$app->response->format = 'json';

        $result = [
            'bodyparts' => [
                'man' => [],
                'woman' => []
            ]
        ];

        if (Yii::$app->request->isAjax) {
            $bodyparts = CheckerBodyparts::find()->where(['status' => CheckerBodyparts::STATUS_ACTIVE])->orderBy('name')->all();

            if ($bodyparts) {
                foreach ($bodyparts as $key => $el) {
                    if ($el->sex_m) {
                        array_push($result['bodyparts']['man'], ['id' => $el->id, 'name' => $el->name]);
                    }
                    if ($el->sex_w) {
                        array_push($result['bodyparts']['woman'], ['id' => $el->id, 'name' => $el->name]);
                    }
                }
            }
        }

        return $result;
    }

    public function actionGetSymptoms()
    {
        Yii::$app->response->format = 'json';

        $post = Yii::$app->request->post();
        $result = [];

        if (Yii::$app->request->isAjax && $post) {
            $bodypart_id = $post['bodypart_id'];
            $bodypart = CheckerBodyparts::findOne($bodypart_id);
            $symptoms = CheckerSymptoms::find()
                ->select(['id', 'name', 'slug AS url'])
                ->joinWith(['symptomRelations'])
                ->where(['bodypart_id' => $bodypart_id, 'status' => CheckerSymptoms::STATUS_ACTIVE])
                ->orderBy('name')
                ->asArray()
                ->all();

            $result['bodypart'] = [
                'name' => $bodypart->name,
                'url' => null
            ];

            if ($symptoms) {
                $result['symptoms'] = $symptoms;
            }
        }

        return $result;
    }

    public function actionFilter($query = null, $key = null)
    {
        Yii::$app->response->format = 'json';

        $out = ['results' => ['id' => '', 'text' => '']];

        if ($query) {
            $select = $key ? ["$key AS id", "name AS text"] : ["id", "name AS text"];

            $model = CheckerSymptoms::find()
                ->select($select)
                ->where(['status' => 10])
                ->andWhere(['like', 'name', $query])
                ->orderBy('name')
                ->limit(30)
                ->asArray()
                ->all();

            $out['results'] = array_values($model);

            return $out;
        }

        return $out;
    }

    protected function findModel($slug)
    {
        if (($model = CheckerSymptoms::find()->where(['slug' => $slug, 'status' => CheckerSymptoms::STATUS_ACTIVE])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function getCity()
    {
        if ($this->botDetected()) {
            $city = null;
        } else {
            $session = Yii::$app->session;
//            if (!isset($session['user_city'])) {
//                $session->set('user_city', $this->getLocationInfo()->city);
//            }
            $city = $session->get('user_city');
        }

        $exists = Employee::find()->joinWith('advisor')->where(['city' => $city, 'employee.status' => Employee::STATUS_ACTIVE])->exists();

        return ($exists) ? $city : null;
    }

    //проверка бота
    protected function botDetected()
    {
        return (
            isset($_SERVER['HTTP_USER_AGENT'])
            && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
        );
    }

    protected function getLocationInfo()
    {
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => "POST"
            ),
        );
        $context = stream_context_create($options);

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $json = @file_get_contents('http://ip-api.com/json/' . $ip . '?lang=ru', false, $context);

        return json_decode($json);
    }
}