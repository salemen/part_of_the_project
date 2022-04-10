<?php
use yii\bootstrap\Modal;

echo Modal::widget([    
    'clientOptions'=>[
        'backdrop'=>'static',
        'keyboard'=>false
    ],
    'id'=>'modal-form',
    'options'=>[
        'tabindex'=>false
    ]   
]);