<?php
// Раздел "Онлайн платежи"

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\forms\PaymentForm;
use app\models\payments\PaymentsOnline;

class PayController extends Controller
{    
    protected $client = null;
    
    public function __construct($id, $module, $config = [])
    {
        $this->client = Yii::$app->odata->connect();
        
        parent::__construct($id, $module, $config);
    }
    
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'process'=>['post']
                ]
            ]
        ];
    } 
    
    // На регистратуре пациент узнает "код заказа", вводит в форму, получает информацию о заказк (перечень услуг, сумма) и оплачивает 
    public function actionIndex()
    {
        $model = new PaymentForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['invoice', 'code'=>$model->code]);
        }
        
        $this->layout = 'form';
        
        return $this->render('index', [
            'model'=>$model
        ]);
    }
    
    public function actionInvoice($code)
    {
        $allModels = [];
        $model = $this->findOrder(str_pad($code, 13, 0, STR_PAD_LEFT));
        $patient = $this->findPatient($model['Пациент_Key']);
        $performer = $this->findPerformer($model['Ref_Key']);
        $services = $model['МедицинскиеУслуги'];
        
        if ($services) {
            foreach ($services as $key=>$service) {
                $nomenclature = $this->findNomenclature($service['Номенклатура_Key']);
                $allModels[$key]['artikul'] = $nomenclature['Артикул'];
                $allModels[$key]['name'] = $nomenclature['Description'];
                $allModels[$key]['cost'] = $service['Цена'];
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$allModels,
            'pagination'=>false,
            'sort'=>false
        ]);
        
        return $this->render('invoice', [
            'dataProvider'=>$dataProvider,
            'model'=>$model,
            'patient'=>$patient,
            'performer'=>$performer
        ]);
    }
    
    public function actionProcess()
    {        
        $data = Yii::$app->request->post();
        
        if ($data) {
            $params = [
                'customerNumber'=>$data['user_id'],
                'serviceType'=>'payments-online',
                'serviceNumber'=>$data['service_id'],
                'sum'=>$data['sum']
            ];

            return Yii::$app->yakassa->payment($params);
        }
        
        throw new NotFoundHttpException('Информации о платеже не найдено.');
    }
    
    protected function findNomenclature($key)
    {
        $model = $this->client->{"Catalog_Номенклатура"}->filter("Ref_Key eq guid'{$key}'")->get()->first();
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Информации о номенклатуре не найдено.');
    }
    
    protected function findOrder($code)
    {
        $model = [];
        $object = $this->client->{"InformationRegister_ШтрихкодыОбъектов"}->expand('Объект')->get("Штрихкод eq '{$code}'")->first();
        
        if ($object !== null) {
            $objectExpanded = $object['Объект_Expanded'];
            
            if ($objectExpanded['DeletionMark'] === false) {
                $Ref_Key = $object['Объект'];
                $model['Ref_Key'] = $Ref_Key;
                $model['ДатаЗаказа'] = $objectExpanded['Date'];
                $model['МедицинскиеУслуги'] = $objectExpanded['МедицинскиеУслуги'];
                $model['НаОплату'] = !PaymentsOnline::isExists($Ref_Key);
                $model['Пациент_Key'] = $objectExpanded['Пациент_Key'];
                $model['СуммаДокумента'] = sprintf('%.2f', $objectExpanded['СуммаДокумента']);
                $model['Штрихкод'] = $object['Штрихкод'];

                return $model;
            }
        }

        throw new NotFoundHttpException('Информации о заказе по запрошеному коду не найдено.');
    }
    
    protected function findPatient($key)
    {
        $model = $this->client->{"InformationRegister_ДанныеПациентов_RecordType"}->filter("Пациент_Key eq guid'{$key}'")->get()->first();
        
        if ($model !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('Информации о пациенте не найдено.');
    }
    
    protected function findPerformer($key)
    {
        $model = $this->client->{"InformationRegister_ЦСМ_ПрактикиЗаказов"}->expand('Практика')->filter("Заказ_Key eq guid'{$key}'")->get()->first();
        
        return ($model !== null) ? $model['Практика'] : null;
    }
}