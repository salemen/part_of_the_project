<?php
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use app\widgets\SearchSymptom;

$this->title = 'Симптомы и болезни';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hide-footer'][] = true;

$items = [
    [
        'label'=>'Мужчина',
        'linkOptions'=>[
            'data-sex'=>'man'
        ]
    ],
    [
        'label'=>'Женщина',
        'linkOptions'=>[
            'data-sex'=>'woman'
        ]
    ]
];
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?= SearchSymptom::widget(['model'=>$searchModel, 'searchUrl'=>'/symptom/search']) ?>
        </div>
    </div>
    <div class="col-md-6">           
        <div class="nav-tabs-custom">
            <?php 
                echo Tabs::widget([
                    'id'=>'symptom-tabs',
                    'items'=>$items,
                    'linkOptions'=>[
                        'class'=>'sex-tabs'
                    ],
                    'navType'=>'nav-tabs nav-justified',
                    'renderTabContent'=>false
                ]);
                
                echo Html::beginTag('div', ['class'=>'row']);                    
                    echo Html::tag('div', null, ['class'=>'col-md-6', 'id'=>'tab-bodyparts']);
                    echo Html::tag('div', null, ['class'=>'col-md-6 hidden-xs hidden-sm', 'id'=>'tab-bodyimages']);
                echo Html::endTag('div');
            ?>
        </div> 
    </div>
    <div class="col-md-6">
        <div class="box box-primary direct-chat direct-chat-warning">
            <div class="box-header text-center with-border">
                <?= Html::tag('h4', null, ['class'=>'box-title symptom-header']) ?>
            </div>
            <?= Html::tag('div', null, ['class'=>'direct-chat-messages symptom-content']) ?>
            <div class="box-footer text-center with-border">
                <?= Html::tag('p', 'Больше полезных статей на ' . Html::a(Html::img('/img/logo/materlife-xs.png'), '#', ['class'=>'symptom-footer-link', 'target'=>'_blank']), ['class'=>'box-title symptom-footer', 'style'=>'font-size: 16px;']) ?>
            </div>
        </div>
    </div>
   
</div>
    <div style='font-size: 80%; padding-bottom:70px'>
        <p>Не является медицинской услугой. Информация носит ознакомительный характер и не может заменить
            очного приема врача. <br> Информация не может быть использована для постановки диагноза и самостоятельного лечения</p>
    </div>

<?php
$this->registerCss('
.body-bg {
   
    width: 100%;
}
.body-part {
    left: 15px;
    opacity: 0;    
    position: absolute;
    transition: all 300ms ease-in-out;
    height: 100%;
    width: calc(100% - 30px);
}
.body-part.hovered {
    opacity: 1;
    transition: all 150ms ease-in-out;
}
.direct-chat-messages {
    height: calc(100vh - 443px);
}
.nav-stacked > li > a {
    padding: 5px 15px;
}

@media (max-width: 767px) {
    .direct-chat-messages {
        height: calc(100vh - 323px);
    }
}
');
$this->registerJs('
$(document).ready(function() {
    getBodyparts();    
});    

$(document).on("click", ".sex-tabs", function(e) {
    var data = localStorage.getItem("data");
    var sex = $(this).data("sex");
    
    if (data) {
        data = JSON.parse(data);
        
        var bodyparts = data.bodyparts[sex];
        var bodyImageDiv = document.createElement("div");
        var bodyImageBg = document.createElement("img");
        var bodyList = document.createElement("ul");
        var imgPath = "/img/checker/" + sex + "/";
        
        bodyImageBg.setAttribute("class", "body-bg");
        bodyImageBg.setAttribute("src", imgPath + "bg.png");        
        bodyImageDiv.appendChild(bodyImageBg);        
        bodyList.setAttribute("class", "nav nav-stacked");
        
        bodyparts.forEach(function (el) {
            var a = document.createElement("a");
            var li = document.createElement("li");
            var img = document.createElement("img");
            
            img.setAttribute("class", "body-part");
            img.setAttribute("src", imgPath + el.id + ".png");
            img.setAttribute("data-bodypart_id", el.id);
            
            a.setAttribute("href", "#");
            a.setAttribute("class", "bodyparts");
            a.setAttribute("data-bodypart_id", el.id);
            a.append(el.name);            
            li.append(a); 
            
            bodyImageDiv.appendChild(img);
            bodyList.appendChild(li);
        });

        $("#tab-bodyimages").html(bodyImageDiv);
        $("#tab-bodyparts").html(bodyList);
        $(".bodyparts").first().click();
    }
    
    e.preventDefault();
});

$(document).on("click", ".bodyparts", function(e) {
    showPreload(".symptom-content");
    $("ul.nav-stacked > li").removeClass("active");
    
    var bodypart_id = $(this).data("bodypart_id");
    
    $.ajax({
        data: { bodypart_id },
        method: "post",
        success: function (response) {
            if (response.length !== 0) {
                var bodypart = response.bodypart;
                var path = (bodypart.url !== null) ? bodypart.url : "";
                var materlifeUrl = "https://materlife.ru/" + path;
                var symptoms = response.symptoms;
                
                $(".symptom-header").html(bodypart.name);
                $(".symptom-footer-link").attr("href", materlifeUrl);
                
                if (symptoms) {
                    var ul = document.createElement("ul");

                    ul.setAttribute("class", "nav nav-stacked");

                    symptoms.forEach(function (el) {
                        var a = document.createElement("a");
                        var li = document.createElement("li");

                        a.setAttribute("href", "/symptom/view/" + el.url);    
                        a.append(el.name);

                        li.append(a); 
                        ul.appendChild(li);
                    });

                    $(".symptom-content").html(ul);
                } else {
                    var div = document.createElement("div");
                    div.setAttribute("style", "padding: 10px; text-align: center;");
                    div.append("Материалов по выбранной категории не найдено.");
                    
                    $(".symptom-content").html(div);
                }
            }          
        },
        url: "/symptom/get-symptoms"
    });
    
    $(this).parent().toggleClass("active");    
    e.preventDefault();
});

$(document).on("mouseenter mouseleave", ".bodyparts", function(e) {
    var bodypart_id = $(this).data("bodypart_id");
    var image = $(".body-part[data-bodypart_id=" + bodypart_id + "]");
    
    image.toggleClass("hovered");    
    e.preventDefault();
});

function getBodyparts() {
    showPreload("#tab-bodyimages");
    showPreload("#tab-bodyparts");
    showPreload("#symptom-content");
    
    $.ajax({
        async: false,
        method: "post",
        success: function (response) {
            if (response.length !== 0) {
                localStorage.setItem("data", JSON.stringify(response));
                $(".sex-tabs").first().click();
            }
        },
        url: "/symptom/get-bodyparts"
    });
}

function showPreload(target) {
    var div = document.createElement("div");
    var i = document.createElement("i");
    
    div.setAttribute("style", "padding: 40px; text-align: center;");    
    i.setAttribute("class", "fa fa-spinner fa-spin fa-3x fa-fw");
    i.setAttribute("style", "color: #193e85;");    
    div.append(i);
    
    $(target).html(div);
}
');