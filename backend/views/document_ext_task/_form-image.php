<?php

use yii\helpers\Html;
use yii\helpers\Url;
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

            <input type="hidden" id="crop_x" name="x"/>
            <input type="hidden" id="crop_y" name="y"/>
            <input type="hidden" id="crop_w" name="w"/>
            <input type="hidden" id="crop_h" name="h"/>

            <input id="img_url" type="hidden" name="image_url" value="<?= $source->url ?>">

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <a href="<?= Url::to(['document_ext_task/index', 'id_ext_plan' => $ext_plan->id_doc_ext_plan])?>" class="btn btn-primary">
                    Finalizar extracción
                </a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row margin-top-10 margin-bottom-10">
        <div class="col-md-12">
            <div class="card-deck">
                <?php
                if (count($model->docImages) > 0) {
                    foreach ($model->docImages as $docImage){
                        echo  ' <div class="card text-center" style="width: 18rem;" >
                                <img class="card-img " src="../../web/'.$docImage->url.$docImage->name.'" alt="'.$docImage->name.'" >
                <div class="card-body">
                     <a class="btn btn-danger margin-bottom-10" href="'.Url::to(['image-delete','id' => $docImage->id_doc_image ]).'">Eliminar</a>
                </div>
            </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 responsive-1024">
            <img  class="img-responsive" id="demo1" src="../../web/uploads/project/source/<?= $source->url?>">
        </div>
    </div>
</div>
