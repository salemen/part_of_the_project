<?php
use yii\helpers\Html;

$this->title = 'Проверка результата теста на COVID-19';
?>

        
<div class="text-center">
    <?= Html::tag('h2', 'Ваш результат', ['class'=>'text-primary']) ?>
    <hr>
    <?php if ($values) {
        $date_result = $values['date_result'];
        $time_result = $values['time_result'];

        $test_result = $values['test_result'];
        switch ($test_result) {
            case 'negative': {
                $result = '<span class="bg-green" style="padding: 3px 10px;">Отрицательный</span>';
                break;
            }
            case 'positive': {
                $result = '<span class="bg-red" style="padding: 3px 10px;">Положительный</span>';
                break;
            }
            case 'pending': {
                $result = '<span class="bg-gray" style="padding: 3px 10px;">Результат не готов</span>';
                break;
            }
        }
        echo "<h4><b>Код исследования:</b> $number</h4>";
        echo "<h4><b>Результат теста:</b> $result</h4>";
        if ($date_result != null) {
            $date_result = date_create("$date_result");
            $datetime = date_format($date_result, "d.m.Y / $time_result");
            echo "<h4><b>Дата/время:</b> $datetime</h4>";
        }                            
        echo '<hr>';
        echo Html::tag('h4', 'Телефон колл-центра: <b>+7 (3822) 90-03-03</b>');
    } else {
        echo "<h4><b>Код исследования: <span class='text-primary'>$number</span>.</b><br>Результатов по запрошенному номеру исследования не найдено!</h4>";
    } ?>
</div>