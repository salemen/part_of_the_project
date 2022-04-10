<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\helpers\AppHelper;

$this->title = 'Дневник вакцинации COVID-19';
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?php if ($model) {
                echo Html::beginTag('div', ['style'=>'font-size: 13px;']);
                    echo Html::tag('p', Html::tag('b', 'Персональные данные'));
                    echo Html::tag('p', $user->fullname);
                    echo Html::tag('p', $user->user_birth . ' (' . AppHelper::calculateAge($user->user_birth, true) . ')');
                    echo Html::tag('p', $user->sex ? 'Мужской' : 'Женский');
                    echo '<br>';

                    echo Html::tag('p', Html::tag('b', 'Первая вакцинация'));
                    echo Html::tag('p', implode(' ', ['Медицинская организация: ', $model->vac_org_1]));
                    echo Html::tag('p', implode(' ', ['Дата введения вакцины: ', $model->vac_date_1]));
                    echo Html::tag('p', implode(' ', ['Препарат: ', $model->vac_name_1]));
                    echo '<br>';

                    echo Html::tag('p', Html::tag('b', 'Вторая вакцинация'));
                    echo Html::tag('p', implode(' ', ['Медицинская организация: ', $model->vac_org_2]));
                    echo Html::tag('p', implode(' ', ['Дата введения вакцины: ', $model->vac_date_2]));
                    echo Html::tag('p', implode(' ', ['Препарат: ', $model->vac_name_2]));
                    echo '<br>';

                    echo Html::tag('p', Html::tag('b', 'Плановое заполнение дневника'));
                    echo Html::tag('p', '1 день после вакцинации');
                    echo Html::tag('p', '2 дня после вакцинации');
                    echo Html::tag('p', '3 дня после вакцинации');
                    echo Html::tag('p', '7 дней после вакцинации');
                    echo Html::tag('p', '14 дней после вакцинации');
                    echo Html::tag('p', '21 день после вакцинации');
                    echo Html::tag('p', '22 дня после вакцинации');
                    echo Html::tag('p', '23 дня после вакцинации');
                    echo Html::tag('p', '28 дней после вакцинации');
                    echo Html::tag('p', '42 дня после вакцинации');
                    echo '<br>';

                    echo Html::tag('p', Html::tag('b', 'Фактическое заполнение дневника'));
                    echo GridView::widget([
                        'dataProvider'=>$dataProvider,
                        'responsive'=>false,
                        'columns'=>[
                            ['class'=>'kartik\grid\SerialColumn'],
                            [
                                'attribute'=>'created_at',
                                'value'=>function ($model) {
                                    return date('d.m.Y H:i:s (время по МСК)', $model->created_at);
                                }
                            ]
                        ]
                    ]);
                echo Html::endTag('div');            
                echo '<hr>';

                echo Html::a('Заполнить дневник', ['form', 'id'=>$model->id], ['class'=>'btn btn-primary']);
            } else {
                echo Html::tag('b', 'Данных о вакцинации по COVID-19 не обнаружено.');
            }
            ?>
        </div>
    </div>
</div>

<?php
$this->registerJs('
iCheckInit();
');