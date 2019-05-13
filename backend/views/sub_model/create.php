<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SubModel */

$this->title = 'Crear componente';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Componentes', 'url' => ['index','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-create">



    <div class="row">
        <div  class="col-md-8">
            <?php
            $form = ActiveForm::begin([
                    'id' => 'submodel_form',
                //'enableAjaxValidation' => true,
                //'enableClientValidation' => true,
            ]);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <!--Canvas -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-square-o"></i> Componente</h2>
                        </div>
                        <div class="box-body">
                            <div id="main-frame" data-force="30" class="layer block">
                                    <!-- <div class="layer title"></div>-->
                                <ul id="submodel" class="block__list block__list_tags"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-list"></i> Datos del componente</p>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model,'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>

                            <?= $form->field($model,'name')->textInput(); ?>

                            <div class="checkbox icheck margin-top-20">
                                <label class="margin-right-10">
                                    <input name="repeat" type="checkbox"> Se repite
                                </label>
                            </div>
                            <div class="checkbox icheck margin-top-10">
                                <label>
                                    <input name="required" type="checkbox"> Requerido
                                </label>
                            </div>

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
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h2 class="box-title"><i class="fa fa-info"></i> Detalles</h2>
                        </div>
                        <div class="box-body">
                            <!-- Descripción del Elemento-->
                            <div  data-force="30" class="layer block" style="width: 100%;">
                                <!-- <div class="layer title"></div>-->
                                <ul id="details" class="block__list block__list_tags">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sidebar-form" class="col-md-2" style="padding-left: 0px; padding-right: 0px">
            <div id="elements_section" class="row">
                <!--Listado de Elementos-->
                <div class="col-md-12">
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
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <p class="box-title"><i class="fa fa-minus"></i> Separadores:</p>
                        </div>
                        <div class="box-body">
                            <ul id="separators" class="block__list block__list_words">
                                <?php
                                foreach ($separators as $separator) {
                                    echo '<li id="'.$separator->id_separator.'" style="font-weight: bolder; background-color: #dd4b39;"><span >'.$separator->representation.'</span> <span>('.$separator->scope.')</span>
                                  <input type="hidden" name="separator-'.$separator->id_separator.'" value="'.$separator->id_separator.'">
                                </li>';
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



