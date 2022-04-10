<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Мои документы';
$this->params['breadcrumbs'][] = $this->title;


//header("Refresh: 2");

echo GridView::widget([
   
    'id'=>'user-docs',
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::button('Загрузить документ', ['id'=>'docs-upload-btn', 'class'=>'btn btn-primary'])
    ],
    'pjax'=>true,
    'responsive'=>true,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],                            
        [
            'attribute'=>'doc_name',
            'value'=>function ($model) {
                return $model->doc_name . '.' . $model->doc_ext;
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{docs-download} {docs-delete}',
            'buttons'=>[
                'docs-delete'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['class'=>'delete', 'title'=>'Удалить', 'pjax-container-id'=>'user-docs-pjax', 'url'=>$url]);
                },
                'docs-download'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-save"></span>', '#', ['class'=>'download', 'title'=>'Скачать документ', 'url'=>$url]);
                }
            ]
        ]
    ]
]);
            
$form = ActiveForm::begin([
    'action'=>Url::to('/user/docs/docs-upload'),
    'id'=>'docs-upload-form',    
    'options'=>[
        'enctype'=>'multipart/form-data'
    ] 
]);
echo $form->field($model, 'file')->fileInput(['id'=>'docs-upload', 'pjax-container-id'=>'user-docs-pjax', 'style'=>'display: none !important;'])->error(false)->label(false);
ActiveForm::end();



$this->registerJs('    
    $("#docs-upload-btn").click(function() {
        $("#docs-upload").click();
    });
    $(document).on("click", ".delete", function(e) {
        var pjax_id = $(this).attr("pjax-container-id");
        var url = $(this).attr("url");   

        $.confirm({
            buttons: {
                confirm: {
                    action: function () {
                        ajaxAction(url, pjax_id)
                    },
                    btnClass: "btn-primary",
                    text: "да"
                },
                cancel: {
                    text: "нет"
                }
            },
            content: "Вы уверены, что хотите удалить данный элемент?",
            theme: "modern",
            title: "Внимание!"              
        });          
        e.preventDefault();
    });
    $(document).on("click", ".download", function(e) {
        $.ajax({
            method: "post",     
            success: function(res) {
                var link = document.createElement("a");
                link.setAttribute("href", "/uploads/" + res["file"]);
                link.setAttribute("download", res["name"]);
                onload = link.click();
            },
            url: $(this).attr("url")
        });
        e.preventDefault();
         
    });
    $(document).on("change", "#docs-upload", function() {    
        var form = $("#docs-upload-form");
        var pjax_id = $(this).attr("pjax-container-id");

        $.ajax({
            contentType: false,
            data: new FormData(form[0]),
            method: "post",
            processData: false,
            success: function(res) {
                if (res == true) {
                    pjaxReload(pjax_id);
                } else {
                    $("#docs-upload").val(null);
                    alert(res);
                }
            },
            url: form.attr("action")
        }); 
        setTimeout(function(){
    window.location.reload();
          },100); 
    });
');
//header("Refresh: 5");
