<?php
namespace app\models\consult;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

class ConsultHistory extends ActiveRecord
{
    const TYPE_BOT = 0;
    const TYPE_MSG = 10;
    const TYPE_FILE = 20;
    
    public static function tableName()
    {
        return 'consult_history';
    }
    
    public function behaviors()
    {
        return [
            'blameable'=>[
                'class'=>BlameableBehavior::className(),
                'createdByAttribute'=>'message_by',
                'updatedByAttribute'=>false
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['message'], 'required'],
            [['consult_id', 'message_type', 'is_read', 'created_at'], 'integer'],
            [['message_by'], 'string', 'max'=>255],
            [['message'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'consult_id'=>'ID Консультации',
            'message'=>'Сообщение',
            'message_by'=>'Отправитель' ,
            'message_type'=>'Тип сообщения',
            'is_read'=>'Сообщение прочитано',
            'created_at'=>'Дата'                       
        ];
    }
    
    public function getConsult()
    {
        return $this->hasOne(Consult::className(), ['id'=>'consult_id']);
    }
    
    public static function renderMessage($file)
    {
        $ext = pathinfo($file)['extension'];
        $result = null;
        
        switch ($ext) {
            case 'bmp':
            case 'jpg':
            case 'jpeg':
            case 'png':
                $result = Html::a(Html::img('/img/file-type/image.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['class'=>'btn-magnific']);
                break;
            case 'doc':
            case 'docx':   
            case 'rft':
                $result = Html::a(Html::img('/img/file-type/word.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['download'=>true]);
                break;
            case 'xls':
            case 'xlsx':    
                $result = Html::a(Html::img('/img/file-type/excel.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['download'=>true]);
                break;
            case 'pdf':
                $result = Html::a(Html::img('/img/file-type/pdf.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['download'=>true]);
                break;
            case 'txt':
                $result = Html::a(Html::img('/img/file-type/text.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['download'=>true]);
                break;
            default:
                $result = Html::a(Html::img('/img/file-type/other.png', ['style'=>'height: 50px;']), Url::to('/uploads/' . $file), ['download'=>true]);
                break;
        }

        return '<b>Документ:</b>  ' . $result;
    }
}