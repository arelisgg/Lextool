<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lex-article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_lex_article') ?>

    <?= $form->field($model, 'id_lemma') ?>

    <?= $form->field($model, 'article') ?>

    <?= $form->field($model, 'finished')->checkbox() ?>

    <?= $form->field($model, 'reviewed')->checkbox() ?>

    <?php // echo $form->field($model, 'id_redaction_plan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
