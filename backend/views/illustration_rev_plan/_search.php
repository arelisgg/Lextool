<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationRevPlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="illustration-rev-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_illustration_rev_plan') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'id_illustration_plan') ?>

    <?= $form->field($model, 'edition')->checkbox() ?>

    <?= $form->field($model, 'id_project') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'finished')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
