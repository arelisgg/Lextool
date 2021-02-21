<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Templates;
use yii\widgets\Breadcrumbs;
use backend\models\TemplateType;
use backend\models\SubModel;
use \backend\models\Element;
use \backend\models\ElementType;

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
            $form = ActiveForm::begin(['id' => 'general_model_form']);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-list"></i> Datos de la plantilla</p>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model,'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>

                            <?= $form->field($model,'name')->textInput(); ?>

                            <?= $form ->field($model, 'id_template_type')->dropDownList(ArrayHelper::map(TemplateType::find()->where(['removed'=>false])->all(),'id_template_type', 'name'),['prompt'=>'-Seleccione una opción-'])?>

                            <?= Html::submitButton('Siguiente', ['class' => 'btn btn-success',
                               "onclick"=>"actionCreate('$model->id_project','$model->id_template', '".Url::to(['/templates/create',])."')"])
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php
            ActiveForm::end();
            ?>
            <div id="details-section" class="row">
                <div class="col-md-12">
                    <!-- Descripción del Elemento-->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-info"></i> Detalles</h2>
                        </div>
                        <div class="box-body">
                            <div  data-force="30" class="layer block" style="width: 100%;">
                                <!-- <div class="layer title">Detalles</div>-->
                                <ul id="details" class="block__list block__list_tags">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>

