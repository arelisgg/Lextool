<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaExtPlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lemma-ext-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_lemma_ext_plan') ?>

    <?= $form->field($model, 'id_project') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'semantic_field') ?>

    <?= $form->field($model, 'finished')->checkbox() ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
