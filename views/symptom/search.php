<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\widgets\SearchSymptom;

$this->title = 'Результат поиска';
$this->params['breadcrumbs'][] = ['label'=>'Симптомы и болезни', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?= SearchSymptom::widget(['model'=>$searchModel, 'searchUrl'=>'/symptom/search']) ?>
        </div>
    </div>    
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div class="row">
                <div class="col-md-5">
                    <?php if ($models) {
                        $num = $pagination->page * $pagination->pageSize;
                        echo Html::beginTag('ul', ['class'=>'nav nav-stacked']);
                        foreach ($models as $model) {
                            $text = ($num + 1) . '. ' . $model->name;
                            $num++;

                            echo Html::tag('li', Html::a($text, ['view', 'id'=>$model->slug]));
                        }
                        echo Html::endTag('ul');
                    } else {
                        echo 'По вашему запросу ничего не найдено.';
                    } ?>
                </div>
            </div>
            <?= LinkPager::widget(['pagination'=>$pagination]) ?>
        </div>
    </div>
</div>