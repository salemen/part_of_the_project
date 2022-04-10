<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\helpers\AppHelper;
use app\models\research\ResearchIndex;
use app\models\research\ResearchType;
use app\models\research\ResearchUnit;

AppAsset::register($this);

$text = '
    Результаты исследований не являются диагнозом. Диагноз выставляет врач, основываясь на совокупности данных анамнеза,
    клинической картины и результатов других диагностических исследований.
    <br>
    Наиболее точная информация о состоянии организма может быть получена при динамическом наблюдении изменений лабораторных показателей.
    Результаты лабораторных исследований, а также их интерпретацию можно посмотреть после регистрации в личном кабинете <b>0323.ru</b>.
';

function renderTableRow($index_id, $unit_id, $value, $sex) {
    $indexName = ResearchIndex::findOne($index_id)->name;
    $unitName = ResearchUnit::getUnitName($unit_id);
    $norms = ResearchIndex::getNorms($index_id, $sex);
    
    // echo Html::beginTag('tr');
    //     echo Html::tag('td', $indexName);
    //     echo Html::tag('td', $value, ['style'=>'text-align: center;']);
    //     echo Html::tag('td', $unitName, ['style'=>'text-align: center;']);
    //     echo Html::tag('td', ($norms) ? $norms[0]['norms'] : '-', ['style'=>'text-align: center;']);
    // echo Html::endTag('tr');
}

function renderTableRowWithInterp($index_id, $unit_id, $value, $sex) {
    $allowedTags = '<p><b><ul><ol><li><br><blockquote>';
    $model = ResearchIndex::getInterpretation($index_id, $value, $unit_id, $sex, null, false);
    
    echo Html::beginTag('tr');
        echo Html::tag('td', strip_tags($model['content'], $allowedTags));
    echo Html::endTag('tr');
}

if ($data) {
    $type = ResearchType::findOne($data->type_id);
               
    // echo Html::beginTag('div', ['class'=>($data->multiple) ? 'page row' : 'row']);
    //     echo Html::beginTag('div', ['class'=>'col-md-8 col-md-offset-2', 'style'=>'margin-top: 30px;']);
        
    //         echo Html::beginTag('table', ['class'=>'table']);
    //             echo Html::beginTag('tbody');
    //                 echo Html::beginTag('tr');
    //                     echo Html::tag('td', Html::img('/img/logo/logo-mobile.jpg', ['class'=>'img-responsive']), ['style'=>'width: 20%;']);
    //                     echo Html::tag('td', Html::tag('h4', $type->name_alt ? $type->name_alt : $type->name, ['style'=>'margin: 2px;']), ['style'=>'text-align: center; width: 60%;']);
    //                     echo Html::tag('td', null, ['style'=>'width: 20%;']);
    //                 echo Html::endTag('tr');
    //             echo Html::endTag('tbody');
    //         echo Html::endTag('table');
        
            echo Html::beginTag('table', ['class'=>'table']);
                echo Html::beginTag('tbody');
                    echo Html::beginTag('tr');
                        echo Html::tag('td', Html::img('/img/graph/interp.png', ['class'=>'img-responsive']), ['style'=>'width: 8%;']);
                        echo Html::beginTag('td', ['style'=>'width: 90%;']);
                            echo Html::beginTag('p');
                                echo Html::tag('b', 'ФИО: ') . $data->user_fullname;
                                echo '<br>';
                                echo Html::tag('b', 'Возраст: ') . AppHelper::calculateAge($data->user_birthday, true);
                                echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                echo Html::tag('b', 'Пол: ') . (($data->user_sex === 'man') ? 'мужской' : 'женский');
                                echo '<br>';
                                echo Html::tag('b', 'Дата исследования: ') . date('d.m.Y г.', strtotime($data->research_date));
                            echo Html::endTag('p');
                        echo Html::endTag('td');
                    echo Html::endTag('tr');
                echo Html::endTag('tbody');
            echo Html::endTag('table');
            
            // echo Html::beginTag('table', ['class'=>'table table-bordered']);
            //     echo Html::beginTag('tbody');
            //         echo Html::beginTag('tr');
            //             echo Html::tag('th', 'Показатель', ['style'=>'width: 40%;']);
            //             echo Html::tag('th', 'Результат', ['style'=>'width: 23%;']);
            //             echo Html::tag('th', 'Единицы', ['style'=>'width: 12%;']);
            //             echo Html::tag('th', 'Референсные значения *', ['style'=>'width: 25%;']);
            //         echo Html::endTag('tr');

                    if ($data->multiple) {
                        foreach ($data->values as $el) {
                            renderTableRow($el->index_id, $el->unit_id, $el->value, $data->user_sex);
                        }
                    } else {
                        renderTableRow($data->index_id, $data->unit_id, $data->value, $data->user_sex);
                    }

                echo Html::endTag('tbody');
            echo Html::endTag('table');
            
            // echo Html::tag('small', '* Референсные значения приводятся с учетом пола, возраста, фазы менструального цикла, срока беременности.', ['style'=>'font-size: 12px;']);
            
            // echo Html::tag('blockquote', $text, ['style'=>'font-family: "TimesNewRoman"; font-size: 14px; margin: 10px 0 0;']);
            
            // echo Html::tag('p', '«Группа компаний ЦСМ САНТАЛЬ»', ['style'=>'font-family: "TimesNewRoman"; font-size: 14px; text-align: center;']);
            
        echo Html::endTag('div');
    echo Html::endTag('div');
    
    echo Html::beginTag('div', ['class'=>($data->multiple) ? 'page row' : 'row']);
        echo Html::beginTag('div', ['class'=>'col-md-8 col-md-offset-2', 'style'=>'margin-top: 30px;']);
        
            echo Html::beginTag('table', ['class'=>'table']);
                echo Html::beginTag('tbody');
                    echo Html::beginTag('tr');
                        echo Html::tag('td', Html::img('/img/logo/logo-mobile.jpg', ['class'=>'img-responsive']), ['style'=>'width: 20%;']);
                        echo Html::tag('td', Html::tag('h4', 'Расшифровка результатов анализов', ['style'=>'margin: 2px;']), ['style'=>'text-align: center; width: 60%;']);
                        echo Html::tag('td', null, ['style'=>'width: 20%;']);
                    echo Html::endTag('tr');
                echo Html::endTag('tbody');
            echo Html::endTag('table');
        
            echo Html::beginTag('table', ['class'=>'table table-bordered']);
                echo Html::beginTag('tbody');
                    if ($data->multiple) {
                        foreach ($data->values as $el) {
                            renderTableRowWithInterp($el->index_id, $el->unit_id, $el->value, $data->user_sex);
                        }
                    } else {
                        renderTableRowWithInterp($data->index_id, $data->unit_id, $data->value, $data->user_sex);
                    }
                echo Html::endTag('tbody');
            echo Html::endTag('table');

            echo Html::tag('p', '«Группа компаний ЦСМ САНТАЛЬ»', ['style'=>'font-family: "TimesNewRoman"; font-size: 14px; text-align: center;']);
            echo Html::tag('blockquote', $text, ['style'=>'font-family: "TimesNewRoman"; font-size: 14px; margin: 10px 0 0;']);
            echo Html::Tag('p'); 
            
        echo Html::endTag('div');
    echo Html::endTag('div');
}

$this->registerCss('
.table > tbody > tr > td {
    border-top: none;    
}
.table > tbody > tr > th {
    text-align: center;    
}
.table {
    font-family: "TimesNewRoman";
    font-size: 14px;
}
@media print {
    blockquote {
        border-bottom: none;
        border-right: none;
        border-top: none;
    }
    .page {        
        page-break-after: always;
    }
}
');
$this->registerJs('
window.print();
');
