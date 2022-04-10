<?php
use kartik\grid\GridView;
use yii\widgets\DetailView;

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label'=>'Справочник: Сотрудники', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'sex',
            'value'=>function ($model) {
                if ($model->sex === null) { return $model->sex; }
                return ($model->sex) ? 'Мужской' : 'Женский';
            }
        ],
        'username',
        'user_birth',
        'email:email',        
        'phone_work',
        'phone',
        'city',
        'snils',
        [
            'attribute'=>'id',
            'label'=>'Звание / Должность',
            'format'=>'raw',
            'value'=>function ($model) {
                $result = '';
                if ($model->degrees) { foreach ($model->degrees as $degree) { $result .= $degree->empl_degree . '<br>'; } }
                if ($model->ranks) { foreach ($model->ranks as $rank) { if ($rank->empl_rank) { $result .= $rank->empl_rank . '<br>'; } } }
                
                return $result;
            }
        ]
    ]
]);

if ($posProvider->getModels()) {
    echo '<br><h4>Место работы</h4>';
    echo GridView::widget([
        'id'=>'pos-grid',
        'dataProvider'=>$posProvider,
        'pjax'=>true,
        'columns'=>[   
            [
                'attribute'=>'org_id',
                'value'=>function ($model) {
                    return $model->org->name;
                }
            ],
            'empl_pos'           
        ]
    ]);
}
if ($qualProvider->getModels()) {
    echo '<br><h4>Сертификаты</h4>';
    echo GridView::widget([
        'id'=>'qual-grid',
        'dataProvider'=>$qualProvider,
        'pjax'=>true,
        'columns'=>[
            'empl_qual',
            'empl_spec'
        ]
    ]);
}
if ($docProvider->getModels()) {
    echo '<br><h4>Другие документы</h4>';
    echo GridView::widget([
        'id'=>'doc-grid',
        'dataProvider'=>$docProvider,
        'pjax'=>true,
        'columns'=>[
            'doc_type',
            'empl_spec'
        ]
    ]);
}   