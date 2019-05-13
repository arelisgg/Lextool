<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationPlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="illustration-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_illustration_plan') ?>

    <?= $form->field($model, 'id_project') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'star_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'finished')->checkbox() ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
