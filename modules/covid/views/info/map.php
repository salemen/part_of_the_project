<?php
$this->title = $title;
?>

<?php if ($model) { ?>
    <div id="accordion" style="margin: 30px 0;">    
        <?php foreach ($model as $key=>$value) { ?>
        <div class="accordion-item">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $value->id ?>" class="collapse-item collapsed" data-id="<?= $value->id ?>" data-type="<?= $type ?>">
                <h4><?= $value->name ?></h4>
            </a>
            <div id="collapse<?= $value->id ?>" class="panel-collapse collapse" aria-expanded="false"></div>
        </div> 
        <?php } ?>
    </div>
<?php } ?>

<?php
$this->registerCss('
.accordion-item {
    background-color: aliceblue;
    border: 1px solid #eee;
    border-left: 5px solid #193e85;
    margin-bottom: 10px;
    padding: 15px 30px 15px 15px;    
}
.accordion-item-child {
    background-color: #ffffff;
    border: 1px solid #ddd;
    padding: 15px 30px 15px 15px;
    margin-top: 10px;
}
.accordion-item-child-desc {
    padding: 15px 30px 15px 15px;
}
');
$this->registerJs('
$(document).on("click", ".collapse-item", function(e) {
    var id = $(this).data("id");    
    var empty = $("#collapse" + id).is(":empty");
    
    if (empty) {
        $.ajax({
            data: $(this).data(),
            method: "post",
            success: function (response) {
                $("#collapse" + response.id).html(response.content).toggleClass("collapse");
            },
            url: "/covid/info/content"
        });
    }
});
');