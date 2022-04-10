<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Разрешения';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Роли и разрешения', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'contentOptions'=>['class'=>'kv-align-middle'],
            'label'=>'Уровень доступа',
            'value'=>function ($model) {
                return $model['name'];
            }
        ],
        [
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 300px;'],
            'format'=>'raw',
            'header'=>'Разрешения',
            'headerOptions'=>['class'=>'kv-align-center'],
            'value'=>function ($model) use ($auth, $employee_id) {
                $allPermissions = $auth->getPermissions();
                $permissions = '';
                if ($allPermissions) {
                    foreach ($allPermissions as $permission) {
                        $childrens = $auth->getChildren($permission->name);
                        foreach ($childrens as $child) {
                            if ($child->name == $model['name']) {
                                $class = empty($auth->getAssignment($permission->name, $employee_id)) ? 'btn-default' : 'btn-primary';
                                $permissions .= Html::a($permission->description, ['toggle-permission', 'employee_id'=>$employee_id, 'permission_name'=>$permission->name], ['class'=>"btn btn-xs $class", 'data-method'=>'post', 'style'=>'margin-right: 3px;']);
                            } else {
                                return "У роли {$model['name']} нет особых разрешений";
                            }
                        }
                    }
                }                
                
                return $permissions;
            }
        ]  
    ]
]) ?>