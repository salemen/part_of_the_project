<?php
namespace app\modules\consult\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use app\helpers\CryptHelper;
use app\models\consult\ConsultHistory;
use app\models\user\UserDocs;
use app\modules\consult\forms\ChatForm;

class MessageController extends Controller
{    
    private $crypter = null;
    
    public function __construct($id, $module, $config = array())
    {
        $this->crypter = new CryptHelper(Yii::$app->params['cryptKey']);
        
        parent::__construct($id, $module, $config);
    }
    
    public function actionCheck()
    {        
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            $consult_id = $post['consult_id'];
            $message_by = Yii::$app->user->id;            
            ConsultHistory::updateAll(['is_read'=>true], "consult_id = {$consult_id} AND message_by != '{$message_by}' AND is_read = 0");
                        
            return [ 'success'=>true ];
        }
        
        return false;
    }
    
    public function actionDelete()
    {
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            $model = ConsultHistory::findOne($post['msg_id']);
            
            if ($model->delete()) {
                return [ 'msg_id'=>$model->id ];
            } else {
                throw new ServerErrorHttpException('Сообщение не было удалено');
            }
        }
    }
    
    public function actionFile()
    {
        Yii::$app->response->format = 'json';
        $model = new ChatForm();
        $model->file = UploadedFile::getInstance($model, 'file');
        $file = $model->file;
            
        if ($file && $model->validate()) {
            $fileName = implode('.', [Yii::$app->getSecurity()->generateRandomString(10), $file->extension]);
            if ($file->saveAs("uploads/{$fileName}")) {
                $this->saveUserDocs($file, $fileName);
                return [ 'file'=>$fileName ];
            } else {
                throw new ServerErrorHttpException('Документ не был сохранен.');
            }
        } else {
            $message = $model->getErrors('file')[0];
            throw new ServerErrorHttpException($message);
        }
    }
    
    public function actionRender()
    {
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            $model = ConsultHistory::findOne($post['msg_id']);
            
            return $this->renderAjax('/site/message', [
                'crypter'=>$this->crypter,
                'model'=>$model
            ]);
        }
    }
    
    public function actionSave()
    {  
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            $crypter = $this->crypter;            
            $model = new ConsultHistory([
                'consult_id'=>$post['consult_id'],
                'message'=>$crypter->encrypt($post['message']),
                'message_type'=>$post['message_type']
            ]);
            
            if ($model->save()) {
                return [ 
                    'msg_id'=>$model->id,
                    'message'=>$this->renderAjax('/site/message', [
                        'crypter'=>$crypter,
                        'model'=>$model
                    ])
                ];
            }
        }
                
        throw new ServerErrorHttpException('Сообщение не было отправлено.');        
    }
    
    public function actionUpdate()
    {  
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        
        if (Yii::$app->request->isAjax && $post) {
            $crypter = $this->crypter;
            $message = $crypter->encrypt($post['message']);
            $model = ConsultHistory::findOne($post['msg_id']);
            
            if ($model) {
                $model->message = $message;
                if ($model->save()) {
                    return [
                        'msg_id'=>$model->id,
                        'message'=>$crypter->decrypt($message)
                    ];
                }
            }
        }
        
        throw new ServerErrorHttpException('Сообщение не было обновлено.');
    }
    
    protected function saveUserDocs($file, $name)
    {
        if (!Yii::$app->session->has('employee_santal')) {
            (new UserDocs([
                'doc_name'=>$file->baseName,
                'doc_ext'=>$file->extension,
                'doc_file'=>$name
            ]))->save();
        }
    }        
}