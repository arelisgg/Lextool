<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lex-article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_lemma')->textInput() ?>

    <?= $form->field($model, 'article')->textInput() ?>

    <?= $form->field($model, 'finished')->checkbox() ?>

    <?= $form->field($model, 'reviewed')->checkbox() ?>

    <?= $form->field($model, 'id_redaction_plan')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
