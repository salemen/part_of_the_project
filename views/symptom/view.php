<?php
use yii\helpers\Html;
use app\widgets\speaker\Widget as SpeakWidget;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Симптомы и болезни', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?= SpeakWidget::widget(['speakText' => 'Прослушать статью: ']) ?>
            <hr>
            <?= Html::tag('h1', $model->name); ?><br/>
            <?= Html::tag('div', $model->content, ['id' => 'speak-text']) ?>
            <?= ($model->symptomRelations) ? $this->render('_part/materlife', ['model' => $model->symptomRelations]) : null ?>
            <?php if ($doctors) { ?>
                <div class="row" style="margin-top: 20px;">
                    <hr>
                    <h4 class="text-center" style="margin-bottom: 40px;">В решении этого вопроса вам могут помочь:</h4>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <?= $this->render('/doctor/_part/employees', ['model' => $doctors]) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>