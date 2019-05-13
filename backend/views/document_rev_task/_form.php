<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>
            <?= $form->field($model, 'id_source')->hiddenInput(['value'=> $source->id_source])->label(false) ?>
            <?= $form->field($model, 'id_doc_type')->hiddenInput(['value' => $ext_plan->id_doc_type ])->label(false) ?>
            <?= $form->field($model, 'id_doc_ext_plan')->hiddenInput(['value' => $ext_plan->id_doc_ext_plan ])->label(false) ?>
            <?= $form->field($model, 'id_user')->hiddenInput(['value' => Yii::$app->user->id ])->label(false) ?>
            <input id="id_lemma" name="id_document" type="hidden" class="form-control" value="<?= $model->id_document ?>">
            <input  name="id_rev_plan" type="hidden" class="form-control" value="<?= $rev_plan->id_rev_plan ?>">

            <?= $form->field($model, 'original_text')->textarea(['rows' => 6,'required' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row margin-bottom-10 margin-top-10">
        <div class="col-md-12">
            <h3><strong>Vista Previa del Documento</strong></h3>
            <iframe id="preview" src="../../web/js/pdfjs/web/viewer.html?file=../../../uploads/project/source/<?= $source->url?>" width="100%" height="500" allowfullscreen></iframe>
        </div>
    </div>
</div>
