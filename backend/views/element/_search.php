<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ElementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="element-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_element') ?>

    <?= $form->field($model, 'id_project') ?>

    <?= $form->field($model, 'id_element_type') ?>

    <?= $form->field($model, 'property') ?>

    <?= $form->field($model, 'visibility')->checkbox() ?>

    <?php // echo $form->field($model, 'font') ?>

    <?php // echo $form->field($model, 'font_size') ?>

    <?php // echo $form->field($model, 'font_weight') ?>

    <?php // echo $form->field($model, 'font_style') ?>

    <?php // echo $form->field($model, 'text_decoration') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'background') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
