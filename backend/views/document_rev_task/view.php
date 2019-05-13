<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */

$this->title = $model->docType->name;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de revisión de documentos complementarios' , 'url' => ['document_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Documentos complementarios' , 'url' => ['document_rev_task/index','id_rev_plan' => $rev_plan->id_rev_plan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="document-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title ?></h2>
            <div class="pull-right">

                <?php
                if (!$model->reviewed) {
                    echo Html::a('<i class="fa fa-check"></i> Aprobar documento',
                            ['aprove', 'id' => $model->id_document, 'id_rev_plan' => $rev_plan->id_rev_plan],
                            ['class' => ' btn btn-primary btn-sm']). " ";
                }else {
                    echo Html::a('<i class="fa fa-close"></i> Desaprobar documento',
                            ['aprove', 'id' => $model->id_document, 'id_rev_plan' => $rev_plan->id_rev_plan],
                            ['class' => ' btn btn-danger btn-sm']). " ";
                }
                ?>

                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>',
                    ['update', 'id' => $model->id_document, 'id_rev_plan' => $rev_plan->id_rev_plan],
                    ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>',
                    ['delete', 'id' => $model->id_document, 'id_rev_plan' => $rev_plan->id_rev_plan], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => 'Está seguro que desea eliminar este documento?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Aprobado</h4>
                            <p class="list-group-item-text"><?= $model->reviewed ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $project->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Tipo de Documento</h4>
                            <p class="list-group-item-text"><?= $rev_plan->extDocPlan->docType->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Fuente</h4>
                            <p class="list-group-item-text"><?= $rev_plan->extDocPlan->source->name ?></p>
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
                    </ul>
                </div>

            </div>

            <div id="lightgallery" class="card-deck">
                <?php
                foreach ($model->docImages as $image) {
                    echo ' 
                        <div class="card bg-dark text-white" data-src="../../web/uploads/project/comp_doc_img/'.$image->name.'" style="padding: 0; box-shadow: 1px 2px 5px rgba(0,0,0,0.5);">
                           <a href="">
                             <img class="card-img" src="../../web/uploads/project/comp_doc_img/'.$image->name.'" alt="'.$image->name.'">
                           </a>
                        </div>
                ';
                }
                ?>
            </div>
        </div>
    </div>

</div>
