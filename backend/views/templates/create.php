<?php

use yii\helpers\ArrayHelper;
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
$elements = Element::find()->where(['id_project'=> $project->id_project ])->all();
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-create">

    <div class="row">
        <div  class="col-md-6">
            <?php
            $form = ActiveForm::begin(['id' => 'general_model_form']);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <!--Canvas -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-sitemap"></i> Crear plantilla</h2>
                        </div>
                        <div class="box-body">

                            <div id="main-frame " data-force="30" class="layer block" style="width: 100%;">
                                <!-- <div class="layer title"></div>-->
                                <ul id="general" class="block__list block__list_tags">
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-list"></i> Datos de la plantilla</p>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model,'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>

                            <?= $form->field($model,'name')->textInput(); ?>

                            <?= $form ->field($model, 'id_template_type')->dropDownList(ArrayHelper::map(TemplateType::find()->all(),'id_template_type', 'name'))?>

                            <button class="btn btn-success margin-top-10 margin-bottom-30" type="submit"> Guardar</button>
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
        <div class="col-md-2">
            <div id="submodels_section" class="row">
                <!--Listado de Submodelos-->
                <div class="col-md-12" style="padding-left: 8px; padding-right: 4px">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-list-alt"></i> Componentes</h2>
                        </div>
                        <div class="box-body">
                            <ul id="submodels" class="block__list block__list_words">
                                <?php
                                $submodels = SubModel::find()->where(['id_project' =>$project->id_project, 'id_template'=> null])->all();
                                foreach ($submodels as $submodel) {
                                    if (!$submodel->repeat && $submodel->required) {
                                        echo '<li class="only-required" id="'.$submodel->id_sub_model.'">
                                                <span id="name" style="font-weight: bold">'.$submodel->name.' </span> 
                                                ( <i class="fa fa-check"></i> )
                                                <p style="margin-bottom: 0px"><small>'.$submodel->getOnlyElementsName().'</small></p>
                                                <input type="hidden" name="submodel-'.$submodel->id_sub_model.'" value="'.$submodel->id_sub_model.'">
                                              </li>';
                                    }elseif (!$submodel->repeat && !$submodel->required) {
                                        echo '<li class="full" id="'.$submodel->id_sub_model.'"> 
                                        <span id="name" style="font-weight: bold">'.$submodel->name.' </span>
                                        <p style="margin-bottom: 0px"><small>'.$submodel->getOnlyElementsName().'</small></p>
                                       <input type="hidden" name="submodel-'.$submodel->id_sub_model.'" value="'.$submodel->id_sub_model.'">
                                       </li>';
                                    }
                                    elseif ($submodel->repeat && $submodel->required) {
                                        echo '<li class="full" id="'.$submodel->id_sub_model.'"> 
                                        <span id="name" style="font-weight: bold">'.$submodel->name.' </span> ( <i class="fa fa-check"></i>, <i class="fa fa-refresh"></i> )
                                        <p style="margin-bottom: 0px"><small>'.$submodel->getOnlyElementsName().'</small></p>
                                       <input type="hidden" name="submodel-'.$submodel->id_sub_model.'" value="'.$submodel->id_sub_model.'">
                                       </li>';
                                    }elseif ($submodel->repeat && !$submodel->required) {
                                        echo '<li class="only-repeat" id="'.$submodel->id_sub_model.'"> 
                                                <span id="name" style="font-weight: bold">'.$submodel->name.'</span> ( <i class="fa fa-refresh"></i> )
                                                <p style="margin-bottom: 0px"><small>'.$submodel->getOnlyElementsName().'</small></p>
                                                <input type="hidden" name="submodel-'.$submodel->id_sub_model.'" value="'.$submodel->id_sub_model.'">
                                             </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sidebar-form" class="col-md-2" >
            <div id="elements_section" class="row">
                <!--Listado de Elementos-->
                <div class="col-md-12" style="padding-left: 8px; padding-right: 4px">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-tags"></i> Elementos:</p>
                        </div>
                        <div class="box-body">
                            <ul id="elements" class="block__list block__list_words">
                                <?php
                                foreach ($elements as $element) {
                                    echo '<li id="'.$element->id_element.'"><span id="name" style="font-weight: bold">'.$element->elementType->name.'</span> <span id="property">('.$element->property.')</span>
                                  <input type="hidden" name="element-'.$element->id_element.'" value="'.$element->id_element.'">
                                </li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div id="separators_section" class="row">
                <!--Listado de Elementos-->
                <div class="col-md-12" style="padding-left: 8px; padding-right: 4px">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-minus"></i> Separadores</h2>
                        </div>
                        <div class="box-body">
                            <ul id="separators" class="block__list block__list_words">
                                <?php
                                foreach ($separators as $separator) {
                                    if ($separator->scope == 'Componente') {
                                        echo '<li id="' . $separator->id_separator . '" style="font-weight: bolder; background-color: #00a65a;"><span>' . $separator->representation . '</span> <span>(' . $separator->scope . ')</span>
                                  <input type="hidden" name="separator-' . $separator->id_separator . '" value="' . $separator->id_separator . '">
                                </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

