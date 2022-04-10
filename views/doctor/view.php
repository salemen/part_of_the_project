<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\consult\Consult;
use app\models\employee\Employee;

$this->title = (isset($model->advisor->seo_title)) ? $model->advisor->seo_title : $model->fullname;
$this->params['breadcrumbs'][] = ['label'=>'Врачи', 'url'=>['index']];
$this->params['breadcrumbs'][] = 'Подробнее о специалисте';
$this->params['custom-title'] = 'Подробнее о специалисте';
$this->registerMetaTag([
    'name'=>'description',
    'content'=>(isset($model->advisor->seo_desc)) ? $model->advisor->seo_desc : 'Онлайн консультация без регистратуры и очередей. Всего три простых шага!!! для получения дистанционной консультации.'
], 'description');
$bio = $model->getBio();
$user = Yii::$app->user;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div class="row">
                <div class="col-md-3">
                    <?= Html::a(Html::tag('div', null, ['class' => 'bg-img-center', 'style' => 'background-image: url(' . Employee::getProfilePhoto($model) . '); height: 230px; width: 190px;']), ['doctor/view', 'id' => $model->id]) ?>
                </div>
                <div class="col-md-6">
                    <div class="info-list">
                        <h1 class="text-primary" style="font-size: 24px; margin-top: 0px;"><?= $model->fullname ?></h1>
                        <p style="font-size: 17px;">  
                        <?php
                            if (($cps = $model->customPositions) !== null) { foreach ($cps as $cp) { echo Html::tag('span', $cp->value, ['style'=>'font-weight: 600;']) . '<br>'; } }
                            if ($model->positionsDoctor) { foreach ($model->positionsDoctor as $pos) { echo '<span style="font-weight: 600;">' . $pos->empl_pos . '</span><br>'; } echo '<br>'; }
                            if ($model->degrees) { foreach ($model->degrees as $degree) { echo '<span>' . $degree->empl_degree . '</span><br>'; } }                         
                            if ($model->ranks) { foreach ($model->ranks as $rank) { if ($rank->empl_rank) { echo '<span>' . $rank->empl_rank . '</span><br>'; } } }
                        ?>
                        </p>   
                        <p class="info-city"><?= ($model->city) ?></p>
                    </div>
                </div>
                <div class="col-md-3" style="text-align: center;">
                    <?php   
                    $cost = Consult::getConsultCost($model->advisor);
                    if ($cost) {
                        $cons = Consult::isConsultSecond($model->id) ? 'Стоимость повторной<br>онлайн-консультации' : 'Стоимость<br>онлайн-консультации';
                        $text = Html::tag('span', ($cost === 0) ? 'Бесплатно' : $cost . ' руб.', ['class'=>'text-danger', 'style'=>'font-size: 22px; font-weight: 600;']);
                        echo Html::tag('p', $cons . ':<br>' . $text, ['class'=>'price']);
                    } ?>
                    <div style="text-align: center">
                        <?php if ($user->isGuest) { 
                            echo Html::a('Записаться на <br>онлайн-консультацию', false, ['class'=>'btn btn-block btn-danger btn-login']);
                        } else {
                            if ($user->id !== $model->id) {
                                if (Consult::isConsultExist($model->id, $user->id)) {
                                    echo Html::a('Начать консультацию', ['/consult'], ['class' => 'btn btn-block btn-success']);
                                } elseif($model->advisors->status == 10) {
                                    echo Html::a('Записаться на <br>онлайн-консультацию', ['doctor/consult-details', 'id' => $model->id], ['class' => 'btn btn-block btn-danger btn-modal']);
                                } elseif($model->advisors->status == 0) {
                                    echo Html::a('Записаться на <br>онлайн-консультацию', ['doctor/consult-none', 'id' => $model->id], ['class' => 'btn btn-block btn-danger btn-modal']);
                                }
                            }
                        } ?>                   
                    </div>    
                </div>
            </div>
            <hr>
            <?php if ($bio) { 
                echo '<h4 class="text-primary" style="font-size: 24px; margin-top: 0px;">Профессиональный профиль</h4>';
                echo '<div style="margin-bottom: 20px;">' . $bio['bio'] . '</div>';
            } ?>
            <div class="row">
                <div class="col-md-12">
                <?php if ($posProvider->getModels()) {
                    echo '<h4 class="text-primary" style="font-size: 24px; margin-top: 0px;">Место работы</h4>';
                    echo GridView::widget([
                        'id'=>'pos-grid',
                        'dataProvider'=>$posProvider,
                        'pjax'=>true,
                        'responsive'=>false,
                        'columns'=>[       
                            [
                                'attribute'=>'org_id',
                                'value'=>function ($model) {
                                    return $model->org->name;
                                }
                            ],
                            'empl_pos'
                        ]
                    ]);
                } ?>
                <?php if ($qualProvider->getModels()) {
                    echo '<h4 class="text-primary" style="font-size: 24px; margin-top: 0px;">Сертификаты</h4>';
                    echo GridView::widget([
                        'id'=>'qual-grid',
                        'dataProvider'=>$qualProvider,
                        'pjax'=>true,
                        'responsive'=>false,
                        'columns'=>[       
                            'empl_qual',
                            'empl_spec'
                        ]
                    ]);
                } ?>   
                <?php if ($docProvider->getModels()) {
                    echo '<h4 class="text-primary" style="font-size: 24px; margin-top: 0px;">Другие документы</h4>';
                    echo GridView::widget([
                        'id'=>'doc-grid',
                        'dataProvider'=>$docProvider,
                        'pjax'=>true,
                        'responsive'=>false,
                        'columns'=>[
                            'doc_type',
                            'empl_spec'
                        ]
                    ]);
                } ?>     
                </div>   
            </div>
        </div>        
    </div>
</div>