<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modelo de artículo';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?= $project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<style>
    #section-create {
        display: grid;
        height: 60vh;
        justify-content: center;
        align-items: center;
    }
    a #create-icon {
        color: #00a65a;
    }
    a #create-icon:hover {
        -webkit-transform: scale(1.5);
        -moz-transform: scale(1.5);
        -ms-transform: scale(1.5);
        -o-transform: scale(1.5);
        transform: scale(1.5);

        -webkit-transition: all 1s;
        -moz-transition: all 1s;
        -ms-transition: all 1s;
        -o-transition: all 1s;
        transition: all 1s;
    }
</style>
<div class="sub-model-index">
    <?php
    if ($exist) {
        echo '
  <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-object-ungroup"></i> Modelo de artículo</h2>
            <div class="pull-right">
                '.Html::a('<i class="glyphicon glyphicon-pencil"></i> ', ['update', 'id_project' => $project->id_project], ['class' => 'btn btn-primary']).'
                <button class="btn btn-danger" id="delete_model"><i class="glyphicon glyphicon-trash"></i>  </button>
            </div>
        </div>
        <div class="box-body">';
        echo '<div class="submodel-container">
        <div id="main-frame " data-force="30" class="layer block" style="width: 100%;">
            <!-- <div class="layer title"></div>-->
            <ul id="general" class="block__list block__list_tags">';
                for($i = 0;$i < count($general_model); $i++) {
                    if ($general_model[$i]->canGetProperty('name')) {
                        if (!$general_model[$i]->repeat && $general_model[$i]->required) {
                            echo '<li class="only-required sub_model" id="'.$general_model[$i]->id_sub_model.'"><span id="name" style="font-weight: bold">'.$general_model[$i]->name.' </span> ( <i class="fa fa-check"></i> )
                                                        <p style="margin-bottom: 0px"><small>'.$general_model[$i]->getOnlyElementsName().'</small></p>
                                                        <input type="hidden" name="submodel-'.$general_model[$i]->id_sub_model.'" value="'.$general_model[$i]->id_sub_model.'">
                                                        </li>';
                        }elseif (!$general_model[$i]->repeat && !$general_model[$i]->required) {
                            echo '<li class="full sub_model" id="'.$general_model[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$general_model[$i]->name.' </span>
                                                      <p style="margin-bottom: 0px"><small>'.$general_model[$i]->getOnlyElementsName().'</small></p>
                                                      <input type="hidden" name="submodel-'.$general_model[$i]->id_sub_model.'" value="'.$general_model[$i]->id_sub_model.'">
                                                   </li>';
                        }
                        elseif ($general_model[$i]->repeat && $general_model[$i]->required) {
                            echo '<li class="full sub_model" id="'.$general_model[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$general_model[$i]->name.' </span> ( <i class="fa fa-check"></i>, <i class="fa fa-refresh"></i> )
                                     <p style="margin-bottom: 0px"><small>'.$general_model[$i]->getOnlyElementsName().'</small></p>
                                       <input type="hidden" name="submodel-'.$general_model[$i]->id_sub_model.'" value="'.$general_model[$i]->id_sub_model.'">
                                       </li>';
                        }elseif ($general_model[$i]->repeat && !$general_model[$i]->required) {
                            echo '<li class="only-repeat sub_model" id="'.$general_model[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$general_model[$i]->name.'</span> ( <i class="fa fa-refresh"></i> )
                                 <p style="margin-bottom: 0px"><small>'.$general_model[$i]->getOnlyElementsName().'</small></p>
                                  <input type="hidden" name="submodel-'.$general_model[$i]->id_sub_model.'" value="'.$general_model[$i]->id_sub_model.'">
                                </li>';
                        }
                    }
                    elseif ($general_model[$i]->canGetProperty('id_separator')) {
                        if ($general_model[$i]->scope == 'Componente') {
                            echo '<li class="separator" id="' . $general_model[$i]->id_separator . '" style="width: 60px; height: 70px; font-weight: bolder; background-color: #00a65a;"><span>' . $general_model[$i]->representation . '</span>
                                  <input type="hidden" name="separator-' . $general_model[$i]->id_separator . '" value="' . $general_model[$i]->id_separator . '">
                                </li>';
                        }
                    }
                }
          echo  '</ul>
        </div>
       </div>
       </div>
    </div>';
     echo '
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
     ';
    } else {
        echo '
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-object-ungroup"></i> Modelo de artículo</h2>
        </div>
        <div class="box-body">
            <div id="section-create" class="row">
               <div class="col-md-12">
                    <div class="text-center">
                       <h1 style="font-weight: bold">Crear modelo de artículo</h1>
                       <a id="create-link" href="'.Url::to(['/general_model/create', 'id_project' => $project->id_project]).'"><i id="create-icon" class="fa fa-plus-circle fa-5x"></i></a> 
                    </div>
               </div>
            </div></div>
            </div>
            ';
    }
    ?>
</div>

