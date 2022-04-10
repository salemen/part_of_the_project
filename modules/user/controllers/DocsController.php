<?php
namespace app\modules\user\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\user\UserDocs;

class DocsController extends Controller
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
    
    public function actionIndex()
    {
        $model = new UserDocs();
        $dataProvider = new ActiveDataProvider([
            'query'=>UserDocs::find()->where(['user_id'=>Yii::$app->user->id])->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);
        
        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'model'=>$model
        ]);
    }
    
    public function actionDocsDelete($id)
    {
        Yii::$app->response->format = 'json';
        $model = UserDocs::findOne($id);
        $path = Yii::getAlias('@webroot') . '/uploads/' . $model->doc_file;
        
        return (unlink($path) && $model->delete()) ? true : false;
    }
    
    public function actionDocsDownload($id)
    {
        Yii::$app->response->format = 'json';
        $model = UserDocs::findOne($id);
        $result = [];
        $result['name'] = $model->doc_name;
        $result['file'] = $model->doc_file;
        
        return $result;?>
        <script>
        location.reload();
        </script>
    <?php
    }
    
    public function actionDocsUpload()
    {
        Yii::$app->response->format = 'json';
        $model = new UserDocs();        
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                $name = Yii::$app->getSecurity()->generateRandomString(10);
                $model->doc_name = $model->file->baseName;
                $model->doc_file = $name . '.' . $model->file->extension;
                $model->doc_ext = $model->file->extension;
                
                if ($model->file->saveAs('uploads/' . $name . '.' . $model->file->extension)) {
                    $model->file = null;
                    return $model->save();
                }
            } else {
                return $model->getErrors('file');
            }
        } 
        
        return false;
    }
}