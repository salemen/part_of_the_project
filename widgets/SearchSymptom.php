<?php
// Поиск для раздела "Симптомы и болезни"

namespace app\widgets;

use Yii;
use kartik\select2\Select2;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

class SearchSymptom extends Widget
{
    public $model;
    public $options = ['class'=>'list-inline'];
    public $show_all = true;
    public $allUrl = 'index';
    public $searchUrl = 'index';

    public function run()
    {
        $model = $this->model;
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');

        $this->renderContent($model, $tag, $options);
    }
    
    protected function renderContent($model, $tag, $options)
    {
        echo Html::beginTag('div', ['style'=>'font-size: 20px; text-align: center;']);
            $form = ActiveForm::begin(['action'=>[$this->searchUrl], 'id'=>'literal-form', 'method'=>'get']);
                echo $form->field($model, 'search')->widget(Select2::className(), [
                    'options'=>[
                        'class'=>'form-control',
                        'id'=>'symptom-select'
                    ],
                    'pluginOptions'=>[
                        'allowClear'=>true,        
                        'ajax'=>[
                            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                            'dataType'=>'json',
                            'delay'=>250,
                            'url'=>Url::to(['/symptom/filter', 'key'=>'slug'])
                        ],
                        'minimumInputLength'=>2,
                        'placeholder'=>'Поиск',
                        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                        'templateSelection'=>new JsExpression('function(data) { return data.text; }')
                    ]
                ])->label(false)->error(false);
                echo $form->field($model, 'lit')->hiddenInput(['id'=>'symptom-literal'])->label(false)->error(false);
                echo Html::tag($tag, $this->renderItems($this->getItems()), $options);
            $form->end();
        echo Html::endTag('div');
        
        Yii::$app->view->registerCss('
            .literal.active {
                font-weight: 700;
            }
        ');
        Yii::$app->view->registerJs('
            $(document).on("change", "#symptom-select", function(e) {
                var value = $(this).val();
                
                window.location = "/symptom/view/" + value;
                e.preventDefault();
            })
            $(document).on("click", ".literal", function(e) {
                $("#symptom-literal").val($(this).attr("value"));
                $("#literal-form").submit();
                e.preventDefault();
            })
        ');
    }        


    protected function renderItems($items)
    {
        $letters = [];
        
        if ($this->show_all && ($this->model->lit)) {
            $letters[] = Html::tag('li', Html::a('Все записи', [$this->allUrl]));
        } 
           
        foreach ($items as $item) {            
            $menu = Html::a($item['label'], [$this->searchUrl, 'lit'=>$item['label']], ['class'=>'literal ' . $this->isActiveItem($item['label']), 'value'=>$item['label']]);
            $letters[] = Html::tag('li', $menu);
        }

        return implode("\n", $letters);
    }
    
    protected function isActiveItem($item)
    {
        $lit = $this->model->lit;
        if ($lit) {
            return ($lit === $item) ? 'active' : null;
        }
    }        

    protected function getItems()
    {
        return [
            ['label'=>'А'],
            ['label'=>'Б'],
            ['label'=>'В'],
            ['label'=>'Г'],
            ['label'=>'Д'],
            ['label'=>'Е'],
            ['label'=>'Ё'],
            ['label'=>'Ж'],
            ['label'=>'З'],
            ['label'=>'И'],
            ['label'=>'Й'],
            ['label'=>'К'],
            ['label'=>'Л'],
            ['label'=>'М'],
            ['label'=>'Н'],
            ['label'=>'О'],
            ['label'=>'П'],
            ['label'=>'Р'],
            ['label'=>'С'],
            ['label'=>'Т'],
            ['label'=>'У'],
            ['label'=>'Ф'],
            ['label'=>'Х'],
            ['label'=>'Ц'],
            ['label'=>'Ч'],
            ['label'=>'Ш'],
            ['label'=>'Щ'],
            ['label'=>'Ы'],
            ['label'=>'Э'],
            ['label'=>'Ю'],
            ['label'=>'Я']
        ];
    }        
}