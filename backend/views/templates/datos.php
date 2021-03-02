<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use backend\models\Templates;
use yii\widgets\Breadcrumbs;
use backend\models\TemplateType;
use backend\models\SubModel;
use \backend\models\Element;


use wbraganca\dynamicform\DynamicFormWidget;


/* @var $this yii\web\View */
/* @var $model backend\models\Templates */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Crear Plantilla';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Plantilla', 'url' => ['index','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
$elements = new Element();
$template_element = new \backend\models\TemplateElement();


?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-create">

    <div class="row">
        <div  class="col-md-12">
            <?php
            yii\widgets\Pjax::begin(['id'=>'new_template_datos']);
            $form = ActiveForm::begin([
                    'id' => 'general_model_form'

            ]);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-list"></i> Datos de la plantilla</p>
                        </div>
                        <div class="box-body">

                            <?= $form->field($model,'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>

                            <div class="col-md-6 ">
                            <?= $form->field($model,'name')->textInput(); ?>
                            </div>

                            <div class="col-md-6 ">
                            <?= $form ->field($model, 'id_template_type')->dropDownList(ArrayHelper::map(TemplateType::find()->where(['removed'=>false])->all(),'id_template_type', 'name','stage'),['prompt'=>'-Seleccione una opción-'])?>
                            </div>

                            <div class="col-md-6 ">
                            <?= $form->field($model, 'ref_file')->widget(FileInput::classname(), [
                                'pluginOptions' => [
                                    'uploadUrl' => Url::to(['']),
                                   // 'previewFileType' => 'any',
                                   // 'initialPreviewAsData' => true,
                                    'purifyHtml' => true,
                                    'showPreview' => false,
                                    'showUpload' => true,
                                    'showRemove' => false,
                                    'browseIcon' => '<i class="glyphicon glyphicon-folder-open"></i> ',
                                    'browseLabel' => '',
                                   // 'initialPreview' => $model->isNewRecord || $model->ref_file == 'null' ? '' : Url::home() . "uploads/templates/ref_file/". $model->ref_file,
                                    'overwriteInitial' => false,
                                    'allowedExtensions' => ['ris',],
                                ],
                                'options'=>['accept'=>'.ris',],
                            ]) ?>
                            </div>

                            <div class="col-md-9" >
                            <?= Html::submitButton('Siguiente', ['class' => 'btn btn-success',
                               "onclick"=>"actionCreate('$model->id_project','$model->id_template', '".Url::to(['/templates/create',])."')"])
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            ActiveForm::end();
            yii\widgets\Pjax::end();
            ?>

        </div>


    </div>

</div>

