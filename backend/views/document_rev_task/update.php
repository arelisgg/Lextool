<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */

$this->title = 'Editar documento complementario: '.$ext_plan->docType->name;

//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de revisión de documentos complementarios' , 'url' => ['document_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Documentos complementarios' , 'url' => ['document_rev_task/index','id_rev_plan' => $rev_plan->id_rev_plan]];
$this->params['breadcrumbs'][] = ['label' => $ext_plan->docType->name , 'url' => ['document_rev_task/view', 'id' => $model->id_document, 'id_rev_plan' => $rev_plan->id_rev_plan]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-create">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title ?></h2>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <h2>Documento: <?=  $ext_plan->docType->name ?></h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php

                    foreach ($extension as $ext) {
                        if ($ext == "pdf" && !$source->editable){
                            echo $this->render('_form-pdf', [
                                'model' => $model,
                                'source' => $source,
                                'ext_plan' => $ext_plan,
                                'project' => $project,
                                'rev_plan' => $rev_plan
                            ]);
                        }elseif ($ext == "jpg" ||
                            $ext == "jpeg" ||
                            $ext == "png") {
                            echo $this->render('_form-image', [
                                'model' => $model,
                                'source' => $source,
                                'ext_plan' => $ext_plan,
                                'project' => $project,
                                'rev_plan' => $rev_plan
                            ]);
                        }else if ($ext == "pdf" && $source->editable){
                            echo $this->render('_form', [
                                'model' => $model,
                                'source' => $source,
                                'ext_plan' => $ext_plan,
                                'project' => $project,
                                'rev_plan' => $rev_plan
                            ]);
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>