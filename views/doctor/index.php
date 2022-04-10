<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Онлайн консультации';
$this->params['breadcrumbs'][] = 'Консультации';
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="col-md-3">
                    <?= Html::a('Поиск по критериям', Url::current(), ['class'=>'btn btn-primary btn-lg btn-block btn-modal btn-search']) ?>   
                </div>
            </div>
            <div class="box-body">
                <?= $this->render('_part/employees', ['model'=>$model, 'pagination'=>$pagination]) ?>
                <?= LinkPager::widget(['pagination'=>$pagination]) ?>
            </div>
        </div>
        <div style='font-size: 80%; padding-bottom:20px'>
            <p>Онлайн консультации носят только информационный и рекомендательный характер,
                не являются диагнозом и не заменяют очной консультации.  </p>
        </div>
    </div>

</div>

<?php
$this->registerCss('
.checkbox, .radio {
    font-size: 15px;
} 
.search-list label {
    width: 100%;
}
');