<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Администрирование: Роли и разрешения';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,    
    'export'=>[
        'showConfirmAlert'=>false,
        'target'=>GridView::TARGET_BLANK
    ],
    'exportConfig'=>[
        GridView::EXCEL=>true
    ],
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success']),
        'heading'=>false
    ],
    'panelBeforeTemplate'=>'{toolbarContainer}{before}<div class="clearfix"></div>',
    'toolbar'=>[
        '{export}',
        '{toggleData}'
    ], 
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'fullname',
            'value'=>function ($model) {
                return $model->fullname;
            }
        ],
        [
            'attribute'=>'id',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 400px;'],         
            'format'=>'raw',
            'header'=>'Роли',
            'headerOptions'=>['class'=>'kv-align-center'],
            'value'=>function ($model) {
                $allRoles = Yii::$app->getAuthManager()->getRoles();                
                $roles = '';
                
                foreach ($allRoles as $role) {
                    $class = empty(Yii::$app->getAuthManager()->getAssignment($role->name, $model->id)) ? 'btn-default' : 'btn-primary';
                    $roles .= Html::a($role->name, ['toggle-role', 'employee_id'=>$model->id, 'role_name'=>$role->name], ['class'=>"btn btn-xs $class", 'data-method'=>'post', 'style'=>'margin-right: 3px;']);
                }
                
                return $roles;
            }
        ],        
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Разрешения',
            'template'=>'{permissions}',
            'buttons'=>[
                'permissions'=>function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>', $url);
                }
            ]
        ]
    ]
]) ?>