<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SeparatorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="separator-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_separator') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'representation') ?>

    <?= $form->field($model, 'scope') ?>

    <?= $form->field($model, 'id_project') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
