<?php
use yii\helpers\Html;

$this->title = 'Расшифровка результатов анализов';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hide-footer'][] = true;
$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Расшифровка результатов лабораторных анализов онлайн'
], 'description');
?>

<div class="row">    
    <div class="col-md-12">
        <div class="box box-body box-primary" style="margin-bottom: 20%";>
            <?php if ($model) { 
                echo Html::tag('h4', 'Выберите вид исследования', ['class'=>'text-center', 'style'=>'margin-bottom: 20px;']);
                echo Html::beginTag('div', ['class'=>'row']);
                    foreach ($model as $key=>$value) {
                        $icon = ($value->icon) ? "/storage/research-type/{$value->icon}" : "/img/logo/logo-bird-xs.png";
                        $button = Html::a("<img src='{$icon}'>" . ($value->name_alt ? $value->name_alt : $value->name) , ['form', 'id'=>$value->id], ['class'=>'btn btn-default btn-lg btn-block btn-social']);
                        echo Html::tag('div', $button, ['class'=>'col-md-6', 'style'=>'margin-bottom: 3px;']);
                    }
                echo Html::endTag('div');
            } ?>
            <div style='font-size: 80%; padding-bottom:20px; padding-top:20px'>
                <p>Результаты расшифровки анализов носят только информационный характер. Интерпретация результатов лабораторных исследований должна рассматриваться в совокупности с данными анамнеза, клинической картиной, инструментальными и другими лабораторными методами исследования. Формулировка окончательного диагноза и выбор терапии осуществляется только врачом.<br>
                    Наиболее точная информация может быть получена при динамическом наблюдении изменений лабораторных показателей.<br>
                    Приведенные показатели и их нормы соответствуют обозначению, принятым в группе компаний ЦСМ-Санталь и могут не совпадать с указанными в бланках Ваших анализов</p>
            </div>
        </div>

    </div>
</div>