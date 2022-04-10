<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\assets\AdminLTEBowerAsset;
use app\widgets\Menu;
use app\models\CommonUser;
use app\models\consult\Consult;

AdminLTEBowerAsset::register($this);

$consults = Consult::getConsults();
$hostUrl = Yii::$app->params['hostUrl'];

function getPanelClass($key, $itemId)
{
    $getId = Yii::$app->request->get('id');

    switch ($getId) {
        case NULL:
            return ($key === 0) ? 'active' : null;
        case $itemId:
            return 'active';
        default:
            return null;
    }
}

$activeClass = Yii::$app->request->get('id') ? 'active' : null;
?>

    <aside class="main-sidebar">
        <section class="sidebar">
            <?php
            echo Menu::widget([
                'items' => [
                    [
                        'icon' => 'external-link',
                        'label' => 'Выйти из меню консультаций',
                        'url' => ['/user/profile']
                    ]
                ],
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree']
            ]);
            if ($consults) {
                foreach ($consults as $key => $value) {
                    if ($value->employee_id == Yii::$app->user->id) {
                        $patient = ($value->employeePatient) ? $value->employeePatient : $value->patient;
                        $fullname = ($patient->fullname !== 'Аноним') ? $patient->fullname : $patient->phone;
                        $photo = CommonUser::getPhoto($value->patient_id);
                    } else {
                        if ($value->employee) {
                            $fullname = $value->employee->fullname;
                        } else {
                            $fullname = null;
                        }
                        $photo = CommonUser::getPhoto($value->employee_id);
                    }
                    $status = ($value->is_end) ? '<i class="fa fa-circle text-success"></i> Завершена' : ($value->is_payd ? '<i class="fa fa-circle" style="color: #f39c12;"></i> Оплачена' : '<i class="fa fa-circle text-danger"></i> Не оплачена');
                    echo Html::beginTag('a', ['href' => Url::to(['index', 'id' => $value->id])]);
                    echo Html::beginTag('div', ['class' => 'user-panel ' . getPanelClass($key, $value->id)]);
                    echo Html::tag('div', Html::img($photo), ['class' => 'image pull-left', 'alt' => '123']);
                    echo Html::beginTag('div', ['class' => 'info pull-right']);
                    echo Html::tag('p', $fullname, ['style' => 'margin-bottom: 10px;']);
                    echo Html::tag('p', $status, ['style' => 'font-size: 12px; margin-bottom: 6px;']);
                    echo $value->is_special ? Html::tag('p', '<i class="fa fa-star" style="color: #f39c12;"></i> Специальная консультация', ['style' => 'font-size: 12px; margin-bottom: 6px;']) : null;
                    echo Html::endTag('div');
                    echo Html::endTag('div');
                    echo Html::endTag('a');
                }
            }
            ?>
        </section>
    </aside>

<?php
$this->registerCss('
.content-wrapper {
    margin-left: 320px;
}    
.main-header .logo {
    width: 320px;
}
.main-header .navbar {
    margin-left: 320px;
}
.main-sidebar {
    width: 320px;
}
.user-panel {
    border-top: 1px solid #ddd;
    min-height: 75px;
}
.user-panel > .image > img {    
    height: auto;
    max-width: 45px;
    width: 100%;
}
.user-panel > .info {
    font-weight: 500 !important;
    line-height: 1;
    padding: 1px 2px 1px 15px;    
    position: absolute;
    left: 55px;
    width: 255px;
}
.user-panel > .info > p {
    font-weight: 500;
}
.user-panel.active > .info {
    color: #ffffff !important;
}
.user-panel.active {
    background-color: #3c8dbc;    
}

@media (max-width: 767px) {
    .content-wrapper {
        margin-left: 0;
    }
    .main-header .navbar {
        margin: 0;
    }
    .main-header .logo, .main-header .navbar {
        width: 100%;
    }
    .main-sidebar {
        -webkit-transform: translate(-320px, 0);
        -ms-transform: translate(-320px, 0);
        -o-transform: translate(-320px, 0);
        transform: translate(-320px, 0);
        width: 320px;
    }
    .sidebar-open .content-wrapper, .sidebar-open .main-footer {
        -webkit-transform: translate(320px, 0);
        -ms-transform: translate(320px, 0);
        -o-transform: translate(320px, 0);
        transform: translate(320px, 0);
    }
}
');
$this->registerJs("var hostUrl = '{$hostUrl}';", View::POS_HEAD);