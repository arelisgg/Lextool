<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\DocType;

/* @var $this yii\web\View */
/* @var $model backend\models\ComplementaryDoc */
/* @var $project backend\models\Project */
/* @var $form yii\widgets\ActiveForm */
/* @var $docTypes backend\models\DocType[] */
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="complementary-doc-form">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="glyphicon glyphicon-book"></i> <?= $this->title ?></h2>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model,'id_doc_type')->widget(Select2::className(),[
                'data' => ArrayHelper::map($docTypes,'id_doc_type','name'),
                'options' => ['placeholder' => 'Seleccione...'],
                'language' => 'es',
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            ?>



            <?= $form->field($model, 'url')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'uploadUrl' => Url::to(['']),
                    'previewFileType' => 'any',
                    'initialPreviewAsData' => true,
                    'purifyHtml' => true,
                    'showPreview' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                    'initialPreview' => $model->isNewRecord || $model->url == 'null' ? '' : Url::home() . "uploads/project/complementary_doc/". $model->url,
                    'overwriteInitial' => false,
                    'allowedExtensions' => ['pdf'],
                ],
                'options'=>['accept'=>'.pdf',],
            ]) ?>

            <div class="form-group" style="text-align: right">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <a href="<?= Url::toRoute("document_import/index?id_project=".$project->id_project) ?>" type="button" class="btn btn-default">Cancelar</a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
