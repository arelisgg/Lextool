<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DocRevPlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doc-rev-plan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_rev_plan') ?>

    <?= $form->field($model, 'id_ext_doc_plan') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'id_project') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
