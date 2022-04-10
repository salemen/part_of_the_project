<?php
use yii\helpers\Html;

if ($model) {
    echo Html::beginTag('blockquote');
        echo Html::tag('p', 'Больше полезных статей на ' . Html::a(Html::img('/img/logo/materlife-xs.png'), 'https://materlife.ru', ['target'=>'_blank']));
        echo Html::beginTag('ul');
            foreach ($model as $value) {
                $sections = $value->bodypart->bodypartSections;
                if ($sections) {
                    foreach ($sections as $section) {
                        echo Html::tag('li', Html::a($section->section->name, "https://materlife.ru/{$section->section->slug}", ['target'=>'_blank']));
                    }
                }
            }
        echo Html::endTag('ul');
    echo Html::endTag('blockquote');
}