<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form ActiveForm */
/**
 * registerJsFile() 中参数的使用与 registerCssFile() 中的参数使用类似。 
 * 在上面的例子中,我们注册了 main.js 文件，并且依赖于 JqueryAsset 类。 
 * 这意味着 main.js 文件将被添加在 jquery.js 的后面。 如果没有这个依赖规范的话，main.js和 jquery.js 两者之间的顺序将不会被定义。
 */

//$this->registerJsFile('http://example.com/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

//\frontend\assets\AppAsset::register($this); //注册资源包
?>
<div class="tools-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => Yii::$app->request->url,
        'enableClientValidation' => true,
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]); ?>

        <p>
            <?=$form->field($model, 'id')->textInput()->hint('can not be empty !')?> 
            <?=$form->field($model, 'name')->error()?>
            <?=$form->field($model, 'age')?>
            <?=$form->field($model, 'phone')->textInput()->error(); //['enableAjaxValidation' => true]?>
            <?=$form->field($model, 'imageFile')->fileInput() ; // 多文件 ['multiple' => true, 'accept' => 'image/*']?>
            <?php 
                if(Yii::$app->session->hasFlash('uploaded')){
                    echo \yii\bootstrap\Alert::widget([
                        'options' => [
                            'class' => 'alert-info',
                        ],
                        'body' => Yii::$app->getSession()->getFlash('uploaded'),
                    ]);
                }
            ?>
        </p>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('part', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- tools-form -->
