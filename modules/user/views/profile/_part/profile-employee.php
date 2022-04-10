<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use app\models\user\UserData;
use app\models\data\Department;
?>


<?php $form = ActiveForm::begin([
        'method' => 'post'
]) ?>
<?php
$items = Yii::$app->params['items'];
$params = Yii::$app->params['params'];
$items2 = Yii::$app->params['items2'];
$params2 = Yii::$app->params['params2'];
$user_data = Yii::$app->params['user_data'];
?>
<div class="row">

    <div class="col-md-12">
        <?php if ($advisor):?>
           <?= $form->field($advisor, 'is_special')->checkbox()->label(false)?>
        <?php endif;?>
    </div>


    <div class="col-md-6">
        <?= $form->field($model, 'fullname')->textInput(['maxlength'=>true, 'class'=>'form-control', 'disabled'=>'true']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'user_birth')->textInput(['maxlength'=>true, 'class'=>'form-control', 'disabled'=>'true']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'email')->textInput(['maxlength'=>true, 'class'=>'form-control', 'disabled'=>'true']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'phone')->textInput(['maxlength'=>true, 'class'=>'form-control', 'disabled'=>'true']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'phone_work')->textInput(['maxlength'=>true, 'class'=>'form-control', 'disabled'=>'false']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'snils')->textInput() ?>
    </div>


    <div class="col-md-12">
        <?= $form->field($user_data, 'address')->textInput(['class'=>'form-control']) ?>
    </div>

    <?php if(isset($model->roles->is_santal) && isset($model->roles->is_official)){ ?>
        <div class="col-md-4">
            <?= $form->field($user_data, 'clinic')->dropDownList($items,$params)->label('Моя поликлиника*',['class' => 'someClass'])?>
        </div>
    <?php }?>

    <div class="col-md-4">
        <?= $form->field($user_data, 'polis_oms_org')->dropDownList($items2,$params2)
            ->label('Страховая компания ОМС*',['class' => 'someClass'])?>
    </div>

    <div class="col-md-4">
        <?= $form->field($user_data, 'polis_oms_number')->textInput(['id'=>'polis_oms_number', 'class'=>'form-control' , 'maxlength'=>'16']) ?>
    </div>

</div>


<?= $form->field($model, 'photo')->widget(FileAPI::className(), [
    'crop'=>true,
    'cropResizeWidth'=>250,
    'cropResizeHeight'=>300,
    'jcropSettings'=>[
        'aspectRatio'=>0.83,
        'bgColor'=>'#ffffff',
        'maxSize'=>[500, 600],
        'minSize'=>[100, 120],
        'keySupport'=>false,
        'selection'=>'100%'
    ],
    'settings'=>[
        'accept'=>'.png, .jpg, .jpeg',
        'url'=>['/site/fileapi-upload']
    ]
]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    </div>


<?php ActiveForm::end() ?>
<?php
$this->registerJs('
iCheckInit();
');
