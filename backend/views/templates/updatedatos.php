<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\TemplateType;
use \backend\models\Element;
use kartik\file\FileInput;


/* @var $this yii\web\View */
/* @var $model backend\models\Templates */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Modificar Plantilla';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Plantilla', 'url' => ['index','id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
$elements = new Element();
$template_element = new \backend\models\TemplateElement();


?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="id_template" class="hidden"><?=$model->id_template?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="sub-model-update">

    <div class="row">
        <div  class="col-md-12">
            <?php
            yii\widgets\Pjax::begin(['id'=>'update_template_datos']);
            $form = ActiveForm::begin(['id' => 'general_model_form']);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-list"></i> Datos de la plantilla</p>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model,'id_project')->hiddenInput(['value' => $model->id_project])->label(false) ?>
                            <div class="col-md-6 ">
                            <?= $form->field($model,'name')->textInput(); ?>
                            </div>
                            <div class="col-md-6 ">
                            <?= $form ->field($model, 'id_template_type')->dropDownList(ArrayHelper::map(TemplateType::find()->where(['removed'=>false])->all(),'id_template_type', 'name','stage'),['prompt'=>'-Seleccione una opción-'])?>
                            </div>
                            <div class="col-lg-6 text-lg-left">
                                <?= $form->field($model, 'ref_file')->widget(FileInput::classname(), [
                                    'pluginOptions' => [
                                        'uploadUrl' => Url::to(['']),
                                        'previewFileType' => 'any',
                                        'initialPreviewAsData' => true,
                                        'purifyHtml' => true,
                                        'showPreview' => false,
                                        'showUpload' => false,
                                        'showRemove' => false,
                                        'initialPreview' => $model->isNewRecord || $model->ref_file == 'null' ? '' : Url::home() . "uploads/templates/ref_file/". $model->ref_file,
                                        'overwriteInitial' => false,
                                        'allowedExtensions' => ['ris'],
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-md-9 ">
                            <?= Html::submitButton('Siguiente', ['class' => 'btn btn-success',
                                "onclick"=>"actionUpdate('$model->id_template', '".Url::to(['/templates/update',])."')"])
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

