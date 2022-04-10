<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Тесты', 'url'=>['test/index']];
$this->params['breadcrumbs'][] = ['label'=>'Группы', 'url'=>['test-group/index', 'test_id'=> $model->test_id]];
$this->params['breadcrumbs'][] = ['label'=>'Вопросы', 'url'=>['index', 'group_id'=>$model->group_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'answers')->widget(MultipleInput::className(), [
    'min'=>($count && $count <= 5) ? $count : 1,
    'max'=>5,
    'columns'=>[
        [
            'name'=>'id',
            'type'=>'hiddenInput',
            'value'=>function($data) {
                return $data['id'];
            }
        ],
        [
            'name'=>'name',
            'type'=>'textarea',
            'title'=>'Ответ',
            'enableError'=>true,
            'options'=>[
                'rows'=>1,
                'style'=>'resize: vertical;'
            ],
            'value'=>function($data) {
                return $data['name'];
            }
        ],
        [
            'name'=>'cost',
            'title'=>'Балл',
            'enableError'=>true,
            'value'=>function($data) {
                return ($data['cost'] !== null) ? $data['cost'] : null;
            }
        ]
    ]
])->label(false) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'group_id'=>$model->group_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

