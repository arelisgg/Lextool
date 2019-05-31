<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationDocument */
/* @var $form yii\widgets\ActiveForm */
/* @var $project backend\models\Project */
/* @var $modelIllustrations backend\models\Illustration[] */
/* @var $documents backend\models\ComplementaryDoc[] */


$this->title = 'Agregar ilustraciones';
$this->params['breadcrumbs'][] = ['label' => "Planes de ilustración" , 'url' => ['illustration/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => "Ilustraciones de documentos" , 'url' => ['illustration_document/index','id_illustration_plan' => $model->id_illustration_plan]];$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="illustration-document-create">

    <div class="illustration-document-form">

        <?php $form = ActiveForm::begin([
            'id' => 'illustration-document-form',
            "options" => ["enctype" => "multipart/form-data"],

        ]); ?>
        <?= $form->field($model, 'id_illustration_plan', ['options' => ['class' => 'hidden']])->textInput() ?>
        <?= $form->field($model, 'continue', ['options' => ['class' => 'hidden']])->input('text') ?>

        <div class="row">
            <div class="col-lg-3">
                <div id="lemmas">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-file-pdf-o"></i> Documentos</h2>
                            <button id="select_all" type="button" class="btn btn-primary btn-sm pull-right" onclick="cambiar()">
                                <i class="fa fa-check-square-o"></i>
                            </button>
                        </div>
                        <div class="box-body" style="height: 675px; padding-left: 10px; overflow-y: auto; padding-bottom: 20px;">
                            <?php
                            for ($i = 0; $i < count($documents); $i++){
                                echo '<div class="checkbox icheck">
                                      <label class="margin-right-10"><input name="documents['.$i.']" type="checkbox">'.$documents[$i]->docType->name.'</label>
                                  </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">

                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 200, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelIllustrations[0],
                    'formId' => 'illustration-document-form',
                    'formFields' => [
                        'name',
                        'url',
                    ],
                ]); ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title"><i class="fa fa-file-image-o"></i> Ilustraciones</h2>
                        <button type="button" class="add-item btn btn-success btn-sm pull-right">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                    </div>

                    <div class="box-body" style="height: 675px; padding-left: 10px; overflow-y: auto; padding-bottom: 20px;">
                        <div class="container-items"><!-- widgetContainer -->
                            <?php foreach ($modelIllustrations as $i => $modelIllustration): ?>
                                <div class="item"><!-- widgetBody -->
                                    <?php
                                    // necessary for update action.
                                    if (!$modelIllustration->isNewRecord) {
                                        echo Html::activeHiddenInput($modelIllustration, "[{$i}]id_illustration");
                                    }else
                                        $modelIllustration->scenario = 'create';
                                    ?>

                                    <div class="col-lg-4" style="padding-left: 0px; padding-right: 0px; height: 330px">

                                        <?= $form->field($modelIllustration, "[{$i}]url")->widget(FileInput::classname(), [
                                            'pluginOptions' => [
                                                'uploadUrl' => Url::to(['']),
                                                'dropZoneTitle' => 'Ilustración',
                                                'previewFileType' => 'any',
                                                'purifyHtml' => true,
                                                'showPreview' => true,
                                                'fileActionSettings' => [
                                                    'showZoom' => true,
                                                    'zoomClass' => 'kv-file-zoom btn btn-kv btn-default btn-outline-secondary margin-top-15',
                                                    'showUpload' => false,
                                                    //'showRemove' => false,

                                                ],
                                                'autoReplace' => true,
                                                'uploadAsync' => true,
                                                /*'initialPreviewConfig' => [
                                                   ['url' => 'delete_image', 'key' => $model->id_project],
                                               ],*/
                                                'showUpload' => false,
                                                'showRemove' => false,
                                                'showCaption' => false,
                                                'browseClass' => 'photoBtnUpd',
                                                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                                                'browseLabel' => ' Seleccionar ilustración',
                                                'overwriteInitial' => true,
                                                'initialPreviewAsData' => true,
                                                'initialPreview' => $modelIllustration->isNewRecord || $modelIllustration->url == 'null' ? '' : Url::home() . "uploads/project/illustration_lemma/". $modelIllustration->url,
                                                'allowedFileExtensions' => ['jpg', 'png', 'jpeg', 'gif', 'mp3', 'mp4',],
                                                'allowedPreviewTypes' => ['image','video','audio'],
                                            ],
                                            //'options'=>['accept'=>'image/*,audio/*,video/*',],
                                            'options'=>['accept'=>'.jpg,.jpeg,.png,.gif,.mp3,.mp4',],
                                        ]) ?>


                                        <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                        <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))"><i class="glyphicon glyphicon-minus"></i></button>

                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php DynamicFormWidget::end(); ?>

            </div>
        </div>


        <div class="form-group" style="text-align: right">
            <?= Html::submitButton( 'Guardar', [
                'class' => 'btn btn-success',
                'onclick' => "selectedLemma()"]) ?>
            <a href="<?= Url::toRoute(["illustration_document/index", 'id_illustration_plan' => $model->id_illustration_plan]) ?>" type="button" class="btn btn-default" >Cancelar</a>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
