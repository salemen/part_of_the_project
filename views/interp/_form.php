<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\research\ResearchIndex;
use app\models\research\ResearchNormsQual;
use app\models\research\ResearchUnit;

$this->title = 'Расшифровка результатов анализов';
$this->params['breadcrumbs'][] = ['label'=>'Выбор вида исследования', 'url'=>['index']];
$this->params['breadcrumbs'][] = 'Расшифровка';
$this->params['hide-footer'][] = true;
$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Расшифровка результатов лабораторных анализов онлайн: ' . $type->name_alt ? $type->name_alt : $type->name
], 'description');

function renderTableRow($form, $model, $el, $collapse = false) {
    $childExists = ResearchIndex::find()->where(['parent_id'=>$el->id])->exists();
    // $toggleBtn = $childExists ? Html::a('<i class="fa fa-angle-double-down" style="font-size: 1.5em;"></i>', ".collapse_{$el->id}", ['data'=>['toggle'=>'collapse'], 'id'=>"toggle_{$el->id}"]) : null;
    // $toggleBtn = $childExists ? Html::a('<i class="fa fa-chevron-down" style="font-size: 1.5em;"></i>', ".collapse_{$el->id}", ['data'=>['toggle'=>'collapse'], 'id'=>"toggle_{$el->id}"]) : null;
    $toggleBtn = $childExists ? Html::a('<i class="fa fa-sort-down" style="font-size: 1.5em;"></i>', ".collapse_{$el->id}", ['data'=>['toggle'=>'collapse'], 'id'=>"toggle_{$el->id}", 'class'=>'btn-arrow__toggle']) : null;
    $rowClass = 'index-row ' . ($collapse ? "collapse index-row__hide-table collapse collapse_{$el->parent_id}" : null);
    
    echo Html::beginTag('tr', ['class'=>$rowClass, 'data'=>['index_id'=>$el->id, 'parent_id'=>$el->parent_id]]);
        echo Html::tag('td', $toggleBtn, ['style'=>'padding: 13px 8px; text-align: center; vertical-align: middle !important;']);
        if ($el->is_group) {
            echo Html::tag('td', $el->name, ['colspan'=>5, 'style'=>'vertical-align: middle !important;']);
        } else {
            echo Html::tag('td', $el->name, ['style'=>'vertical-align: middle !important;' . ($collapse ? 'padding-left: 8px;' : null)]);
            
            if ($el->grade_id == ResearchIndex::GRADE_COL) {
                $inputValue = $form->field($model, "values[$el->id][value]")->textInput([
                    'autocomplete'=>"off",
                    'class'=>'form-control interpform-value',
                    'data'=>[
                        'index_id'=>$el->id
                    ],
                    'maxlength'=>true
                ]);
            } else {
                $inputValue = $form->field($model, "values[$el->id][value]")->widget(Select2::className(), [
                    'data'=>ArrayHelper::map(ResearchNormsQual::find()->where(['index_id'=>$el->id])->all(), 'norm_value', 'norm_value'),                    
                    'options'=>[
                        'class'=>'form-control interpform-value',
                        'data'=>[
                            'index_id'=>$el->id
                        ]                        
                    ],
                    'pluginOptions'=>[
                        'allowClear'=>true,
                        'placeholder'=>''
                    ]
                ]);
            }
            
            echo Html::tag('td', $inputValue->error(false)->label(false));
            echo Html::tag('td', null, ['class'=>'interpform-norm', 'data'=>['index_id'=>$el->id], 'style'=>'text-align: center; vertical-align: middle !important;']);
            
            $with = ($el->grade_id == ResearchIndex::GRADE_COL) ? ['researchNormsCol'] : ['researchNormsQual'];
            $map = ResearchUnit::find()->joinWith($with)->where(['index_id'=>$el->id, 'research_unit.status'=>10])->all();
            
            echo Html::tag('td', $form->field($model, "values[$el->id][unit_id]")->widget(Select2::className(), [
                'data'=>ArrayHelper::map($map, 'id', function ($item) { return ($item->id === 28) ? null : $item->name; }),
                'options'=>[
                    'class'=>'interpform-unit_id',
                    'data'=>[
                        'index_id'=>$el->id
                    ]                    
                ]
            ])->error(false)->label(false));
            echo Html::tag('td', Html::a('Расшифровать', ['interp-one'], ['class'=>'btn btn-xs btn-primary interp-result', 'data'=>['index_id'=>$el->id]]), ['style'=>'text-align: center; vertical-align: middle !important;']);
        }
    echo Html::endTag('tr');
}
?>

<div class="row" id="interp-main" data-type_id="<?= $type->id ?>" data-interp_url="<?= Url::to(['/user/analysis'])?>">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?php if ($indexes) {
                $form = ActiveForm::begin(['action'=>'interp-many']);
                    echo $this->render('_part/header', ['model'=>$model, 'type'=>$type, 'user'=>$user]);
                    // echo Html::a(Html::input('text', 'Поиск', '', ['class' => 'input-search-analysis', 'placeholder' => 'Поиск показателя']), ['filter', 'type_id'=>$type->id], ['class'=>'btn-modal']);
                    echo Html::beginTag('div', ['class'=>'row']);
                        echo Html::beginTag('div', ['class'=>'col-md-12 table-box__wrap']);
                            echo Html::beginTag('table', ['class'=>'table table-bordered table-analysis']);
                                echo Html::beginTag('tbody');
                                echo Html::beginTag('tr');
                                    echo Html::tag('th', '', ['class' => 'table-name__num']);
                                    echo Html::tag('th', 'Показатель', ['class' => 'table-name table-name__indicator']);
                                    echo Html::tag('th', 'Значение', ['class' => 'table-name table-name__value']);
                                    echo Html::tag('th', 'Норма', ['class' => 'table-name table-name_norma']);
                                    echo Html::tag('th', 'Ед. измерения', ['class' => 'table-name table-name__dimension']);
                                    echo Html::tag('th', '', ['class' => 'table-name table-name__decryption']);
                                echo Html::endTag('tr');
                                foreach ($indexes as $index) {
                                    renderTableRow($form, $model, $index);
                                    $childs = ResearchIndex::find()->where(['parent_id'=>$index->id, 'status'=>10])->orderBy('name')->all();
                                    if ($childs) {
                                        foreach ($childs as $child) {
                                            renderTableRow($form, $model, $child, true);
                                        }
                                    }
                                }
                                echo Html::endTag('tbody');
                            echo Html::endTag('table');
                        echo Html::endTag('div');
                        echo Html::beginTag('div', ['class'=>'col-md-6']);
                            echo Html::a('Расшифровать все', ['interp-many'], ['class'=>'btn btn-primary interp-all-result']);
                        echo Html::endTag('div');
                        // echo Html::beginTag('div', ['class'=>'col-md-6 search-form-analysis']);
                        //     echo Html::tag('p', 'Не нашли необходимый показатель? Воспользуйтесь ' . Html::a('поисковой формой', ['filter', 'type_id'=>$type->id], ['class'=>'btn-modal']) . '.', ['class'=>'text-right text-mob']); 
                        // echo Html::endTag('div');
                    echo Html::endTag('div');
                $form->end();
            } ?>
        </div>
        <div style='font-size: 80%; padding-bottom:70px'>
            <p>Результаты расшифровки анализов носят только информационный характер. Интерпретация результатов лабораторных исследований должна рассматриваться в совокупности с данными анамнеза, клинической картиной, инструментальными и другими лабораторными методами исследования. Формулировка окончательного диагноза и выбор терапии осуществляется только врачом.<br>
                Наиболее точная информация может быть получена при динамическом наблюдении изменений лабораторных показателей.<br>
                Приведенные показатели и их нормы соответствуют обозначению, принятым в группе компаний ЦСМ-Санталь и могут не совпадать с указанными в бланках Ваших анализов</p>
        </div>
    </div>
</div>

<?php
$this->registerCss('
table.table tbody tr th {
    text-align: center;
}
table.table tbody tr td .form-group {
    margin-bottom: 0;
}
.table-bordered > tbody > tr > th,
.table-bordered > tbody > tr > td {
    border: 1px solid #666;
}
tr.collapse, tr.collapsing {
    background-color: #f6f6f6;
}
.animate-color {    
    -webkit-animation-duration: 2000ms;
    -webkit-animation-iteration-count: 1;
    -webkit-animation-name: toggleColor;
    -webkit-animation-timing-function: ease-in-out;
}
@keyframes toggleColor {
    0%, 66% {
        color: #eb2a23;
        text-decoration: underline;
    }
    33%, 100% {        
        color: #333333;
    }
}
');
$this->registerJs('
iCheckInit();

$("#interpform-is_pregnant").on({
    ifChecked: function() {
        $("#interpform-user_sex").val("woman").trigger("change");
        $("#interpform-user_sex").prop("disabled", true);
    },
    ifUnchecked: function() {        
        $("#interpform-user_sex").val("woman").trigger("change");
        $("#interpform-user_sex").prop("disabled", false);
    }
});

$(".interpform-value").keydown(function(e) {
    if (e.which === 13) {
        var index = $(".interpform-value").index(this);
        var nextInput = $(".interpform-value").eq(index + 1);
        
        if (nextInput.length > 0) {
            var indexId = nextInput.data("index_id");
            var childs = $("tr[data-parent_id=" + indexId + "]");
            
            if (childs.length > 0) {
                if (childs.hasClass("collapse") && !childs.hasClass("in")) {
                    $("#toggle_" + indexId).click();
                }
            } else {
                var parentTr = nextInput.parents("tr.index-row");
                
                if (parentTr.hasClass("collapse") && !parentTr.hasClass("in")) {
                    $("#toggle_" + parentTr.data("parent_id")).click();
                }
            }
        } else {
            nextInput = $(".interpform-value").eq(0);
        }
        
        nextInput.focus();
    }
});

$(document).ready(function() {
    var sex = $("#interpform-user_sex").data("value") || $("#interpform-user_sex").val();
    
    getNorms();
    setAllNorms(sex);
});

$(document).on("click", ".interp-lk", function(e) {
    var url = $("#interp-main").data("interp_url");
    
    window.open(url, "_blank");
    e.preventDefault();
});

$(document).on("click", ".interp-result", function(e) {    
    var user_birthday = $("#interpform-user_birthday").data("value") || $("#interpform-user_birthday").val();
    var user_fullname = $("#interpform-user_fullname").data("value") || $("#interpform-user_fullname").val();  
    var user_sex = $("#interpform-user_sex").data("value") || $("#interpform-user_sex").val();
    var research_date = $("#interpform-research_date").val();
    var isPregnant = $("#interpform-is_pregnant").prop("checked") || false;
    var type_id = $("#interp-main").data("type_id");
    var index_id = $(this).data("index_id");
    var unit_id = $("#interpform-values-" + index_id + "-unit_id").val();
    var value = $("#interpform-values-" + index_id + "-value").val();
    var url = $(this).attr("href");
    
    if (isPregnant) { user_sex = "pregnant"; }
    
    var data = {multiple: false, user_birthday, user_fullname, user_sex, research_date, type_id, index_id, unit_id, value};
    
    $.ajax({
        data: data,
        method: "post",
        success: function (response) {
            var buttons = setAlertButtons(data, response);
            
            $.alert({
                buttons: buttons,
                columnClass: "col-md-5 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
                content: response.content,
                title: false,
                type: response.type
            });
        },
        url: url
    });
    
    e.preventDefault();
});

$(document).on("click", ".interp-all-result", function(e) {
    var user_birthday = $("#interpform-user_birthday").data("value") || $("#interpform-user_birthday").val();
    var user_fullname = $("#interpform-user_fullname").data("value") || $("#interpform-user_fullname").val();  
    var user_sex = $("#interpform-user_sex").data("value") || $("#interpform-user_sex").val();
    var research_date = $("#interpform-research_date").val();
    var isPregnant = $("#interpform-is_pregnant").prop("checked") || false;
    var type_id = $("#interp-main").data("type_id");    
    var values = [];
    var url = $(this).attr("href");
    
    $(".index-row").each(function (i) {
        var index_id = $(this).data("index_id");
        var unit_id = $("#interpform-values-" + index_id + "-unit_id").val();
        var value = $("#interpform-values-" + index_id + "-value").val();
        
        if (value === "" || value === undefined) { return; }        

        values.push({index_id, unit_id, value});
    });    
    
    if (isPregnant) { user_sex = "pregnant"; }
    
    var data = {multiple: true, user_birthday, user_fullname, user_sex, research_date, type_id, values};
    
    $.ajax({
        data: data,
        method: "post",
        success: function (response) {            
            if (response.success === true) {
                var buttons = setAlertButtons(data, response);
                
                $.alert({                    
                    boxWidth: "70%",
                    buttons: buttons,
                    content: response.content,
                    title: false,
                    useBootstrap: false
                });
            } else {
                $.alert({
                    columnClass: "col-md-5 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
                    content: response.content,
                    title: false,
                    type: response.type
                });
            }
        },
        url: url
    });
    
    e.preventDefault();
});

$(document).on("change", ".interpform-unit_id", function() {
    var index_id = $(this).data("index_id");
    var sex = $("#interpform-user_sex").val() || $("#interpform-user_sex").data("value");
    var unit_id = $(this).val();
    
    setNormByUnit(index_id, sex, unit_id);
});

$(document).on("change", "#interpform-user_sex", function() {
    setAllNorms($(this).val());
});

$(document).on("input", ".interpform-value", function() {
    var index_id = $(this).data("index_id");
    var resBtn = $(".interp-lk[data-index_id=" + index_id + "]");
    
    resBtn.html("Расшифровать");
    resBtn.addClass("btn-primary interp-result").removeClass("btn-success interp-lk");
});

function getNorms(type) {
    var type_id = $("#interp-main").data("type_id");
    
    $.ajax({
        async: false,
        data: { type_id },
        method: "post",
        success: function (response) {
            if (response.length !== 0) {
                localStorage.setItem("norms", JSON.stringify(response));
            }
        },
        url: "/interp/get-norms"
    });
}

function setNormByUnit(index_id, sex, unit_id) {
    var data = localStorage.getItem("norms");
    
    if (data) {
        data = JSON.parse(data);
        
        var result = "-";
        var norms = data[index_id];
        var obj = norms.filter(item => item.index_id == index_id && item.unit_id == unit_id);

        if (obj[0] !== undefined) {
            var isPregnant = $("#interpform-is_pregnant").prop("checked");
            result = isPregnant ? obj[0].norms["pregnant"] : obj[0].norms[sex];
        }
        
        $("td.interpform-norm[data-index_id=" + index_id + "]").html(result);
        $("#interpform-values-" + index_id + "-value").val(null);
    } else {
        throwError("Показатели нормы не найдены");
    }
}

function setAllNorms(sex) {
    var data = localStorage.getItem("norms");
    
    if (data) {        
        data = JSON.parse(data);
        
        $(".interpform-norm").each(function (i) {
            var result = "-";
            var index_id = $(this).data("index_id");
            var norms = data[index_id];
            var unit_id = $(`#interpform-values-${index_id}-unit_id`).val();
            var obj = norms.filter(item => item.index_id == index_id && item.unit_id == unit_id);
            
            if (obj[0] !== undefined) {   
                var isPregnant = $("#interpform-is_pregnant").prop("checked");
                result = isPregnant ? obj[0].norms["pregnant"] : obj[0].norms[sex];
            }

            $(this).html(result);
        });
    } else {
        throwError("Показатели нормы не найдены");
    }
}

function setAlertButtons(data, response) {
    var buttons = {};
            
    if (response.actions !== null) { 
        if (response.actions.save === true) {
            buttons["save"] = {
                action: function () {
                    $.ajax({
                        data: data,
                        method: "post",
                        success: function (response) {
                            if (response === true) {
                                if (data.multiple === true) {
                                    $.each(data.values, function (index, obj) {
                                        toggleInterpButton(obj.index_id);
                                    });
                                } else {
                                    toggleInterpButton(data.index_id);
                                }                                
                            } else {
                                throwError("Данные не были сохранены.");
                            }
                        },
                        url: "/interp/save"
                    });
                },
                btnClass: "btn-primary",
                text: "Сохранить в ЛК"
            };
        }
        if (response.actions.save === true) {
            if (response.actions.print === true) {
                buttons["print"] = {
                    action: function () {
                        window.open("/interp/print?json=" + JSON.stringify(data));
                    },
                    btnClass: "btn-info",
                    text: "Распечатать"
                };
            }
        }
        if (response.actions.save !== true) {
                     
            buttons["login"] = {
                btnClass: "btn btn-login btn-info",
                text: "Распечатать"
            };
        }    
    }

    buttons["ok"] = { btnClass: "btn-default", text: "Закрыть" };
    
    return buttons;
}

function toggleInterpButton(index_id) {
    var resBtn = $(".interp-result[data-index_id=" + index_id + "]");
    
    resBtn.html("Сохранено в ЛК");
    resBtn.addClass("btn-success interp-lk").removeClass("btn-primary interp-result");
}

function throwError(content) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-danger",
                text: "ok"
            }
        },
        content: content,
        title: "Ошибка"
    });
}
');