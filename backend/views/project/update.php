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


/* @var $this yii\web\View */
/* @var $model backend\models\Project */
/* @var $modelUserProject backend\models\UserProject */


$this->title = 'Editar proyecto: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_project]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="project-update">

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
                            ])->label("Jefe de Proyecto") ?>
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
                            ])->label("Rango de Fecha"); ?>
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
                                    'allowedExtensions' => ['jpg', 'png', 'doc', 'docx', 'pdf', 'xlsx', 'xls'],
                                ],
                            ]) ?>
                        </div>

                    </div>

                    <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
                </div>

                <div class="form-group" style="text-align: right">
                    <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <a href="<?= Url::toRoute("project/index") ?>" type="button" class="btn btn-default" >Cancelar</a>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>
