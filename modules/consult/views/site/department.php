<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\data\Department;
use app\models\employee\EmployeePosition;

$this->title = 'Выбор подразделения';

$user = Yii::$app->user;
if ($user->identity->roles->is_santal) {
    $depArray = Department::find()->where(['is_santal'=>1, 'status'=>10])->orderBy('name')->all();
} else {
    $orgIds = EmployeePosition::getOrgIds();
    $depArray = Department::find()->where(['status'=>10])->andWhere(['IN', 'org_id', $orgIds])->orderBy('name')->all();

}

$items = ArrayHelper::map($depArray, 'id', function($item) { return $item->name . ' (' . $item->address . ')';});
$params = [
    'prompt' => 'Выберите поликлинику'
];
$model2 = $model->emposition->empl_dep;
$clinic = Yii::$app->request->post('UserData');

?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-12" style="padding: 30px;">
        <?php
            echo $form->field($model, 'dep_id')->widget(Select2::className(), [
                'data'=>ArrayHelper::map($depArray, 'id', function($item) { return $item->name . ' (' . $item->address . ')';}),
                'options'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Выберите подразделение',
                    
                ]
            ]);
        ?>

        <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary btn-block']) ?>        
    </div> 
</div>

<?php ActiveForm::end() ?>