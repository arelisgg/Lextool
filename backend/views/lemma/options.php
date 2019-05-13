<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="lemma-form" style="padding: 10px">

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Proyecto</label>
                <div><?=$model->project->name?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Letra</label>
                <div><?=$model->letter->letter?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Plan de extracción</label>
                <div><?=$model->lemmaExtPlan->ext_plan_name?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Fuente</label>
                <div><?=$model->source->name?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Usuario</label>
                <div><?=$model->user->full_name?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Aprobado</label>
                <div><?=$model->agree?"Si":"No"?></div>
            </div>
        </div>
    </div>


    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'extracted_lemma')->textInput(['required' => true]) ?>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Lema original</label>
                <div><?=$model->original_lemma?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>Elemento lexicográfico</label>
                <div><?=$model->substructure?></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>Homónimo</label>
                <div><?=$model->homonym?"Si":"No"?></div>
            </div>
        </div>
    </div>

    <?=$model->original_text != "" ? '<div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Texto original</label>
                                                <div>'.$model->original_text.'</div>
                                            </div>
                                        </div>
                                    </div>':''?>

    <?=$model->remark != "" ? '<div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Obesrvaciones</label>
                                                <div>'.$model->remark.'</div>
                                            </div>
                                        </div>
                                    </div>':''?>

    <div class="form-group" style="text-align: right">
        <?=Html::button('<i class="glyphicon glyphicon-eye-open"></i> Imágenes', [
            "onclick"=>"image('$model->id_lemma')",
            'class' => 'btn btn-info',
            "title"=>"Ver imágenes contextuales"])?>
        <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Agregar', ['class' => 'btn btn-success', "title"=>"Agregar al lemario"]) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i> Eliminar', ['lemma/delete_complete', 'id' => $model->id_lemma], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
            "title"=>"Eliminar completamente"
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
