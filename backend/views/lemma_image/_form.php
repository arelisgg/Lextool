<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lemma-image-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_lemma')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'url')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
