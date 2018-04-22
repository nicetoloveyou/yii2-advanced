<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<h1>tools/index</h1>
<p><?=$data?></p>
<p><?=$this->render('@front_public/datetime')?></p>
<p><?php $form = \yii\widgets\ActiveForm::begin() ?></p>
<p>
<?=$form->field($model, 'username')?>
<?=$form->field($model, 'auth_key')?>
<?=\yii\helpers\Html::encode($message)?>
</p>
<div class="form-group">
    <?= Html::submitButton(Yii::t('part', 'Submit'), ['class' => 'btn btn-primary']) ?>
</div>
<p><?php \yii\widgets\ActiveForm::end(); ?></p>


<p><?=frontend\components\ListWidget::widget(['items' => [['id'=> 1, 'name'=>'jim'], ['id'=> 2, 'name'=>'dom'], ]]); ?></p>

<p><?=$this->registerLinkTag([
    'title' => 'Live News for Yii',
    'rel' => 'alternate',
    'type' => 'application/rss+xml',
    'href' => 'http://www.yiiframework.com/rss.xml/',
])?></p>

<p><?=yii\jui\DatePicker::widget([
    'name' => 'date',
    'language' => 'en',
//     'model' => $model,
//     'attribute' => 'from_date',
    'clientOptions' => [
        'dateFormat' => 'yy-mm-dd'
    ],
])?>
</p>

<p>
<?php $hello_widget = \frontend\components\HelloWidget::begin([]);?>
    content that may contain <tag>'s ...
    this is hellowidget
    <p>
    <?php 
//         // 调用行为   
//         $widget = new \frontend\components\HelloWidget();
//         //$behavior = $widget->getBehavior('mybehavior');
//         var_dump($widget->what());
//         exit; 

    ?>
    </p>
<?php \frontend\components\HelloWidget::end();?>
</p>

<p><?php echo \yii\helpers\Url::to(['tools/index'], true);?></p>

<p><?php echo \yii\bootstrap\Alert::widget([
    'options' => [
        'class' => 'alert-info',
    ],
    'body' => 'Say hello...',
]);?></p>



<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>
