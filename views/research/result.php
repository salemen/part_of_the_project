<?php
use yii\helpers\Html;
use app\models\research\ResearchType;

$this->title = 'Результат';
$this->params['breadcrumbs'][] = ['label'=>'Просмотр результатов анализов', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

function showTable($arr, $city, $form, $row, $number) {
    echo Html::beginTag('table', ['class'=>'table table-bordered']);
    echo Html::beginTag('tr');
        $exists = ResearchType::find()->where(['id'=>$row['FORM_ID'], 'status'=>10])->exists();
        $btn0 = ($exists) ? Html::a('Расшифровка анализов', ['/interp/form', 'id'=>$row['FORM_ID']], ['class'=>'btn btn-xs btn-success', 'style'=>'margin-right: 3px;', 'target'=>'_blank']) : null;
        $btn1 = Html::a('Записаться на прием к специалисту', 'https://330003.org', ['class'=>'btn btn-xs btn-danger', 'style'=>'margin-right: 3px;', 'target'=>'_blank']);
        $btn2 = Html::a('<i class="fa fa-print"></i> На печать', '#', ['class'=>'btn btn-xs btn-default btn-print', 'data'=>['num'=>$number, 'form'=>$row['FORM_ID'], 'rid'=>$row['REG_ID'], 'city'=>$city, 'oi'=>0]]);
        echo Html::tag('th', $form . Html::tag('span', $btn0 . $btn1 . $btn2, ['class'=>'pull-right']), ['colspan'=>2, 'style'=>'background-color: #eee;']);
    echo Html::endTag('tr');
    
    for ($i=1; $i <= sizeof($arr); $i++) {
        if ($arr[$i]['res'] != '') {
            echo Html::beginTag('tr');
                echo Html::tag('td', $arr[$i]['name'], ['class'=>'col-md-3']);
                echo Html::tag('td', $arr[$i]['res'], ['class'=>'col-md-9']);
            echo Html::endTag('tr');
        }
    }
    echo Html::endTag('table');
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?php
            $connect = Yii::$app->firebird->connect() or die();
            $query = "SELECT reg.reg_id, reg.reg_number_studies, reg.reg_date, rs.rs_id, form.form_name, form.form_id FROM registration reg, reg_services rs, forms form" .
                " WHERE (reg.reg_date>='$from') AND(reg.reg_date<='$to') AND (reg.reg_id=rs.rs_reg_id) AND(reg.reg_number_studies='$number') AND(rs.rs_name=form.form_id) AND(form.isVisible = '1')";
            $res = ibase_query($connect, $query) or die();
            $status = 0;
            #echo Html::tag('h3', 'Код исследования: ' . $number, ['style'=>'color: red; margin-bottom: 10px;']);
			
			echo "<H3>Код исследования: <span style='color: red; margin-bottom: 20px;'>$number</span></H3><BR>";
            
            while ($row = ibase_fetch_assoc($res)) {
                
				// ТУТ СТОЯЛ СТАТУС 1 //
				
				
				
                $form = iconv("cp1251", "utf8", $row['FORM_NAME']);
                $rs_id = iconv("cp1251", "utf8", $row['RS_ID']);
                $query2 = "SELECT rp.rp_result, p.p_id, p.p_name FROM reg_parameters rp LEFT JOIN parameters p ON rp.rp_name=p.p_id WHERE rp.rp_rs_id='$rs_id'";
                $res2 = ibase_query($connect, $query2) or die();
				$arr = [];
                $key = 1;
                while ($row2 = ibase_fetch_assoc($res2)) 
				{
					
				$status = 1;	
					
                    $arr[$key]['id'] = iconv("cp1251", "utf8", $row2['P_ID']);
                    $arr[$key]['res'] = iconv("cp1251", "utf8", $row2['RP_RESULT']);
                    $arr[$key]['name'] = iconv("cp1251", "utf8", $row2['P_NAME']);
                    $key++;
                }		
                if (sizeof($arr) > 0) {
                    if ($form == 'Общий анализ мочи') {
                        $false = 'yes';
                        for ($i=1; $i <= sizeof($arr); $i++) {
                            if($arr[$i]['id'] == 111 && !empty($arr[$i]['res'])) {	
                                $false = 'no';
                            }
                        }
                        if ($false == 'no') {
                            showTable($arr, $city, $form, $row, $number);
                        }

//////////////////// Добавил Дергачев (Если ОАМ не готов, выводится сообщение)


            if ($false == "yes") {
                echo "<BR><span style='color: red'>Внимание! По запрошенному коду исследования ($number) некоторые результаты еще не готовы.</span>";
            } 

////////////////////////////////////////////////////////////////////////////////


                    } 
					
					
					elseif ($form == 'Мазок на флору') {
                        $false = 'yes';
                        for ($i=1; $i <= sizeof($arr); $i++) {
                            if ($arr[$i]['id'] == 140 && !empty($arr[$i]['res'])) {
                                $false = 'no';
                            }
                        }
					

//////////////////// Добавил Дергачев (Если МАЗОК НА ФЛОРУ не готов, выводится сообщение)


/*
            if ($false == "yes") {
                echo "<BR><span style='color: red'>Внимание! По запрошенному коду исследования ($number) некоторые результаты еще не готовы.</span>";
            } 
*/

////////////////////////////////////////////////////////////////////////////////



                        if ($false == 'no') {
                            showTable($arr, $city, $form, $row, $number);
                        }			
                    } else {
                        showTable($arr, $city, $form, $row, $number);
                    }		
                }	 
            }
            
			if ($status == 0) {
                echo Html::tag('p', 'Результатов по запрошенному коду исследования не найдено, либо результат еще не готов.');
				echo Html::tag('p', 'Проверьте корректность вода кода исследования или введите другой код.');
                
				echo"<HR>";
				
				echo Html::a('Ввести другой код', ['index'], ['class'=>'btn btn-md btn-primary']);
            }



/*			
elseif ($status!= 1){
	                echo Html::tag('p', 'По запрошенному номеру исследования некоторые результаты еще не готовы.');
                echo Html::a('Ввести другой код', ['index'], ['class'=>'btn btn-md btn-primary']);

	
}
*/


#echo $status;

			
			?>
        </div>         
    </div>    
</div>

<?php
$this->registerJs('
$(".btn-print").on("click", function(e) {
    var data = $(this).data();
    var params = new URLSearchParams(data).toString();
    var target = "_blank";
    var url = "https://330003.org/data/print.php?" + params;
    
    window.open(url, target);    
    e.preventDefault();
});
');
