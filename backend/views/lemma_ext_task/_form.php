<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Letter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lemma-form">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>
            <?= $form->field($model, 'id_letter')->hiddenInput(['value' => $letter->id_letter])->label(false) ?>
            <?= $form->field($model, 'id_source')->hiddenInput(['value'=> $source->id_source])->label(false) ?>
            <?= $form->field($model, 'id_lemma_ext_plan')->hiddenInput(['value' => $ext_plan->id_lemma_ext_plan ])->label(false) ?>
            <?= $form->field($model, 'id_user')->hiddenInput(['value' => Yii::$app->user->id ])->label(false) ?>

            <?= $form->field($model, 'extracted_lemma')->textInput(['required' => true]) ?>
            <?= $form->field($model, 'original_lemma')->textInput(['required' => true]) ?>


            <?= $form->field($model, 'original_text')->textarea(['rows' => 6,'required' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row margin-bottom-30">
        <div class="col-md-12">
            <h3><strong>Vista previa del documento</strong></h3>
            <iframe id="preview" src="../../web/js/pdfjs/web/viewer.html?file=../../../uploads/project/source/<?= $source->url?>" width="100%" height="500" allowfullscreen></iframe>
        </div>
    </div>
</div>
