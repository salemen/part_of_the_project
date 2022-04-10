<?php
use yii\helpers\Html;
use app\models\menu\MenuItem;
use app\models\menu\MenuSection;

$sections = MenuSection::find()->where(['is_on_mega'=>true, 'status'=>10])->orderBy('name')->all();
?>

<div id="mega-menu" class="dropdown-menu mega-dropdown-menu">
    <div class="container">
        <div class="row">
            <?php if ($sections) {                
                foreach ($sections as $section) {
                    $items = MenuItem::find()->where(['section_id'=>$section->id, 'is_on_mega'=>true, 'status'=>10])->orderBy('name')->all();
                    if ($items) {
                        echo Html::beginTag('div', ['class'=>'col-sm-6', 'style'=>'margin-bottom: 20px;']);
                            echo Html::beginTag('ul');
                                echo Html::tag('li', $section->name, ['class'=>'dropdown-header']);
                                foreach ($items as $item) {
                                    $class = (Yii::$app->user->isGuest) ? $item->class_guest : $item->class_default;
                                    $img = ($item->photo_small) ? Html::img('/storage/menu-item-small/' . $item->photo_small, ['class'=>'img-thumbnail']) : null;
                                    echo Html::tag('li', Html::a($img . ' ' . $item->name, $item->url, ['class'=>$class, 'target'=>($item->is_blank) ? '_blank' : '_self']));
                                }
                            echo Html::endTag('ul');
                        echo Html::endTag('div');
                    }
                }
            } else {
                $this->registerJs('$("#mega-menu-toggle").remove();');
            } ?>
        </div>
    </div>
</div>

<?php
$this->registerCss('
#mega-menu-toggle {
    border: 0;
    color: #333;
    display: block;
    font-size: 20px;
    margin: 0;
    padding: 10px 15px;
    width: 47px;
}    
.mega-dropdown {
    position: static !important;
}
.mega-dropdown-menu {
    -webkit-box-shadow: none;
    box-shadow: none;
    height: calc(100vh - 48px);
    overflow-y: auto;
    padding: 20px 0px;    
    position: fixed;
    top: 48px;
    width: 100%;    
}
.mega-dropdown-menu ul {
    padding-left: 20px;    
}
.mega-dropdown-menu ul > li {
    font-size: 18px;
    list-style: none;
    margin: 5px;
    padding: 0;    
}
.mega-dropdown-menu > ul > li > a {
    display: block;
    color: #222;
    padding: 3px 5px;
}
.mega-dropdown-menu > ul > li > a:focus,
.mega-dropdown-menu > ul > li > a:hover {
    text-decoration: none;
}
.mega-dropdown-menu .dropdown-header {
    color: #eb2a23;
    font-size: 20px;
    line-height: 30px;
    padding: 5px 60px 10px 0px;    
}
');
$this->registerJs('
$("#mega-menu-toggle").on("click", function(e) {
    var target = $(this).data("target");
    var iconClass = ($(target).css("display") === "block") ? "fa fa-bars" : "fa fa-remove";

    $(target).toggle();
    $(this).html("<i class=\"" + iconClass + "\"></i>");
    e.preventDefault();
});
');