<?php
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;

switch ($bodyClass) { 
    case 'skin-black':
    case 'skin-black-light':
        $boxClass = 'box-black';
        break;
    case 'skin-blue':
    case 'skin-blue-light':
        $boxClass = 'box-blue';
        break;
    case 'skin-green':
    case 'skin-green-light':
        $boxClass = 'box-green';
        break;
    case 'skin-purple':
    case 'skin-purple-light':
        $boxClass = 'box-purple';
        break;
    case 'skin-red':
    case 'skin-red-light':
        $boxClass = 'box-red';
        break;
    case 'skin-yellow':
    case 'skin-yellow-light':
        $boxClass = 'box-yellow';
        break;
    default:
        $boxClass = 'box-default';
        break;
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $this->title ?></h1>
        <?= Breadcrumbs::widget(['links'=>isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
    </section>
    <section class="content">
        <div class="box <?= $boxClass ?>">
            <div class="box-header">
                <?= $content ?>
                <?= Alert::widget() ?>
            </div>
        </div>                     
    </section>
</div>