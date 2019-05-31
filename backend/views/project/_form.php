<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\file\FileInput;
use dosamigos\datepicker\DateRangePicker;
use backend\models\DictionaryType;
use common\models\User;
use wbraganca\dynamicform\DynamicFormWidget;
use backend\models\Letter;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */
/* @var $modelTeams backend\models\UserProject[] */
/* @var $modelSources backend\models\Source[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-book"></i> <?= $this->title?></h2>
        </div>
        <div class="box-body" style="margin: 10px">

            <?php $form = ActiveForm::begin([
                'id' => 'project-form',
                "method" => "post",
                "options" => ["enctype" => "multipart/form-data"],
                //'enableAjaxValidation' => true,
            ]); ?>

            <div class="col-lg-3 text-lg-left">
                <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                    'id'=>'image-project',
                    'name' => 'input-ru[]',
                    'language' => 'en',
                    'options' => ['multiple' => false, 'accept' => 'image/*'],
                    'pluginOptions' => [
                        'dropZoneTitle' => 'Imagen',
                        'previewFileType' => 'image',
                        'uploadUrl' => Url::to(['']),
                        'showCaption' => false,
                        'showUpload' => false,
                        'showRemove' => false,
                        'browseClass' => 'photoBtnUpd',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => ' Seleccionar foto',
                        'autoReplace' => true,
                        'uploadAsync' => true,
                        'fileActionSettings' => [
                            'showZoom' => false,
                            'showUpload' => false,
                        ],
                        'initialPreviewAsData' => true,
                        'overwriteInitial' => true,
                        'initialPreview' => $model->isNewRecord ? '' : $model->getImageUrl() . "",
                        'initialPreviewConfig' => [
                            ['url' => 'delete_image', 'key' => $model->id_project],
                        ],
                    ],
                ])->label(false); ?>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'name')->textInput() ?>
                    </div>

                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'id_dictionary_type')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(DictionaryType::find()->where(['removed' => false])->all(), 'id_dictionary_type', 'type'),
                            'options' => [
                                'placeholder' => 'Seleccione...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => ['Planificación' => 'Planificación', 'Elaboración' => 'Elaboración', 'Revisión' => 'Revisión',],
                            'options' => [
                                'placeholder' => 'Seleccione...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                    </div>

                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'id_user')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(User::find()->joinWith('authAssignments')
                                ->where(['enabled' => true, 'item_name' => 'Jefe de Proyecto'])->orderBy('full_name')
                                ->all(), 'id_user', 'full_name'),
                            'options' => [
                                'placeholder' => 'Seleccione...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label("Jefe de proyecto") ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'start_date')->widget(
                            DateRangePicker::className(), [
                            'attributeTo' => 'end_date',
                            'form' =>$form,
                            'language' => 'es',
                            'labelTo' => '-',
                            'clientOptions' =>[
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                'daysOfWeekDisabled' => '0.6',
                                'minView' => 0,

                            ]
                        ])->label("Rango de fecha"); ?>
                    </div>

                    <div class="col-lg-6 text-lg-left">
                        <?= $form->field($model, 'plant_file')->widget(FileInput::classname(), [
                            'pluginOptions' => [
                                'uploadUrl' => Url::to(['']),
                                'previewFileType' => 'any',
                                'initialPreviewAsData' => true,
                                'purifyHtml' => true,
                                'showPreview' => false,
                                'showUpload' => false,
                                'showRemove' => false,
                                'initialPreview' => $model->isNewRecord || $model->plant_file == 'null' ? '' : Url::home() . "uploads/project/plant/". $model->plant_file,
                                'overwriteInitial' => false,
                                'allowedExtensions' => ['jpg', 'jpeg', 'png', 'doc', 'docx', 'odt', 'rtf', 'docm', 'pdf',],
                            ],
                            'options'=>['accept'=>'.jpg,.jpeg,.png,.doc,.docx,.odt,.rtf,.docm,.pdf',],
                        ]) ?>
                    </div>

                </div>

                <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
            </div>

            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a name="tab-1" href="#tab-1" data-toggle="tab">Equipo</a></li>
                            <li><a name="tab-2" href="#tab-2" data-toggle="tab">Fuentes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab-1">
                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'team_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.team-items', // required: css class selector
                                    'widgetItem' => '.team', // required: css class
                                    'limit' => 200, // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-team', // css class
                                    'deleteButton' => '.remove-item', // css class
                                    'model' => $modelTeams[0],
                                    'formId' => 'project-form',
                                    'formFields' => [
                                        'id_user',
                                        'role',
                                    ],
                                ]); ?>
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h2 class="box-title"><i class="fa fa-group"></i> Equipo</h2>
                                        <button type="button" class="add-team btn btn-success btn-sm pull-right">
                                            <i class="glyphicon glyphicon-plus"></i>
                                        </button>
                                    </div>
                                    <div class="box-body" style="padding-left: 0px; padding-right: 0px">

                                        <div class="team-items"><!-- widgetContainer -->
                                            <?php foreach ($modelTeams as $i => $modelTeam): ?>
                                                <div class="team"><!-- widgetBody -->
                                                    <?php
                                                    // necessary for update action.
                                                    if (!$modelTeam->isNewRecord) {
                                                        echo Html::activeHiddenInput($modelTeam, "[{$i}]id_user_project");
                                                    }
                                                    ?>
                                                    <div class="col-lg-11" style="padding-left: 0px; padding-right: 0px">
                                                        <div class="col-lg-6">
                                                            <?= $form->field($modelTeam,"[{$i}]id_user")->widget(Select2::className(),[
                                                                'data' => ArrayHelper::map(User::find()->joinWith('authAssignments')
                                                                    ->where(['enabled' => true, 'item_name' => 'Especialista'])
                                                                    ->orderBy('full_name')
                                                                    ->all(), 'id_user', 'full_name'),
                                                                'options' => ['placeholder' => 'Seleccione...'],
                                                                'language' => 'es',
                                                                'pluginOptions' => [
                                                                    'allowClear' => true,
                                                                ],
                                                            ]);
                                                            ?>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <?= $form->field($modelTeam,"[{$i}]role")->widget(Select2::className(),[
                                                                'data' => [
                                                                    'Lexicógrafo' => 'Lexicógrafo',
                                                                    'Colaborador' => 'Colaborador',
                                                                ],
                                                                'options' => ['placeholder' => 'Seleccione...'],
                                                                'language' => 'es',
                                                                'pluginOptions' => [
                                                                    'allowClear' => true,
                                                                ],
                                                            ]);
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1"  style="padding-left: 0px; padding-right: 0px; margin-top: 30px; text-align: center">
                                                        <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                                        <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))">Eliminar</button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php DynamicFormWidget::end(); ?>
                            </div>

                            <div class="tab-pane fade" id="tab-2">
                                <?php DynamicFormWidget::begin([
                                    'widgetContainer' => 'source_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                    'widgetBody' => '.source-items', // required: css class selector
                                    'widgetItem' => '.source', // required: css class
                                    'limit' => 200, // the maximum times, an element can be cloned (default 999)
                                    'min' => 1, // 0 or 1 (default 1)
                                    'insertButton' => '.add-source', // css class
                                    'deleteButton' => '.remove-item', // css class
                                    'model' => $modelSources[0],
                                    'formId' => 'project-form',
                                    'formFields' => [
                                        'name',
                                        'url',
                                        'letter',
                                    ],
                                ]); ?>
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h2 class="box-title"><i class="fa fa-file-text"></i> Fuentes</h2>
                                        <button type="button" class="add-source btn btn-success btn-sm pull-right">
                                            <i class="glyphicon glyphicon-plus"></i>
                                        </button>
                                    </div>
                                    <div class="box-body" style="padding-left: 0px; padding-right: 0px">

                                        <div class="source-items"><!-- widgetContainer -->
                                            <?php foreach ($modelSources as $i => $modelSource): ?>
                                                <div class="source"><!-- widgetBody -->
                                                    <?php
                                                    // necessary for update action.
                                                    if (!$modelSource->isNewRecord) {
                                                        echo Html::activeHiddenInput($modelSource, "[{$i}]id_source");
                                                    }else
                                                        $modelSource->scenario = 'create';
                                                    ?>
                                                    <div class="col-lg-11" style="padding-left: 0px; padding-right: 0px">
                                                        <div class="col-lg-3">
                                                            <?= $form->field($modelSource, "[{$i}]name")->textInput() ?>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <?= $form->field($modelSource, "[{$i}]url")->widget(FileInput::classname(), [
                                                                'pluginOptions' => [
                                                                    'uploadUrl' => Url::to(['']),
                                                                    'previewFileType' => 'any',
                                                                    'initialPreviewAsData' => true,
                                                                    'purifyHtml' => true,
                                                                    'showPreview' => false,
                                                                    'showUpload' => false,
                                                                    'showRemove' => false,
                                                                    'browseIcon' => '<i class="glyphicon glyphicon-folder-open"></i> ',
                                                                    'browseLabel' => '',
                                                                    //'initialPreview' => $model->isNewRecord || $model->url == 'null' ? '' : Url::home() . "uploads/project/source/". $model->url,
                                                                    'overwriteInitial' => false,
                                                                    'allowedExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                                                                ],
                                                                'options'=>['accept'=>'.jpg,.jpeg,.png,.pdf',],
                                                            ]) ?>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <?= $form->field($modelSource,"[{$i}]letter")->widget(Select2::className(),[
                                                                'data' => ArrayHelper::map(Letter::find()->orderBy('letter')->all(),'id_letter','letter'),
                                                                'options' => ['placeholder' => 'Seleccione...',],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true,
                                                                    'multiple' => true,
                                                                ],
                                                            ]);
                                                            ?>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <?= $form->field($modelSource, "[{$i}]editable")->widget(Select2::classname(), [
                                                                'data' => [1=>'Sí', 0=>'No'],
                                                                'options' => [
                                                                    'placeholder' => 'Seleccione...',
                                                                ],
                                                                'pluginOptions' => [
                                                                    'allowClear' => true,
                                                                ],
                                                            ]) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1" style="padding-left: 0px; padding-right: 0px; margin-top: 30px; text-align: center">
                                                        <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                                        <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))">Eliminar</button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php DynamicFormWidget::end(); ?>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="text-align: right">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <a href="<?= Url::toRoute("project/index") ?>" type="button" class="btn btn-default" >Cancelar</a>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
