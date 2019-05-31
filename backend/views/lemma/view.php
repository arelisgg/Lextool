<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */

$this->title = $model->extracted_lemma;
$this->params['breadcrumbs'][] = ['label' => 'Lemario', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->project->name?></div>
<div class="lemma-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title ?></h2>
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id_lemma], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => '¿Está seguro de eliminar este lema del lemario?',
                        'method' => 'post',
                    ],
                    "title"=>"Eliminar del lemario"
                ]) ?>
            </div>
        </div>
        <div class="box-body">

            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $model->project->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Plan de extracción</h4>
                            <p class="list-group-item-text"><?=$model->lemmaExtPlan->ext_plan_name?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Letra</h4>
                            <p class="list-group-item-text"><?= $model->letter->letter ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Fuente</h4>
                            <p class="list-group-item-text"><?= $model->source->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Usuario</h4>
                            <p class="list-group-item-text"><?=$model->user->full_name?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Aprobado</h4>
                            <p class="list-group-item-text"><?= $model->agree ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Lema extraído</h4>
                            <p class="list-group-item-text"><?= $model->extracted_lemma ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Homónimo</h4>
                            <p class="list-group-item-text"><?= $model->homonym ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Lema original</h4>
                            <p class="list-group-item-text"><?= $model->original_lemma ?></p>
                        </li>

                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Elemento lexicográfico</h4>
                            <p class="list-group-item-text"><?= $model->substructure ?></p>
                        </li>
                        <?php
                        if ($model->original_text != "" && $model->original_text != null) {
                            echo  '
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Texto Original</h4>
                            <p class="list-group-item-text">
                                '.$model->original_text.'
                            </p>
                        </li>';
                        }
                        ?>
                        <?php
                        if ($model->remark != "") {
                            echo  '
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Observaciones</h4>
                            <p class="list-group-item-text">
                                '.$model->remark.'
                            </p>
                        </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="lightgallery" class="card-deck">
                <?php
                foreach ($model->lemmaImages as $image) {
                    echo ' 
                        <div class="card bg-dark text-white" data-src="../../web/uploads/project/source_images/'.$image->name.'" style="padding: 0; box-shadow: 1px 2px 5px rgba(0,0,0,0.5);">
                           <a href="">
                             <img class="card-img" src="../../web/uploads/project/source_images/'.$image->name.'" alt="'.$image->name.'">
                           </a>
                        </div>
                ';
                }
                ?>
            </div>

        </div>
    </div>

</div>
