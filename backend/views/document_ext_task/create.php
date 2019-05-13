<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */

$this->title = 'Extraer documento complementario';

//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de extracción de documentos complementarios' , 'url' => ['document_ext_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Documentos complementarios' , 'url' => ['document_ext_task/index','id_ext_plan' => $ext_plan->id_doc_ext_plan]];
$this->params['breadcrumbs'][] = $this->title
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
                                    'project' => $project
                                ]);
                            }elseif ($ext == "jpg" ||
                                $ext == "jpeg" ||
                                $ext == "png") {
                                echo $this->render('_form-image', [
                                    'model' => $model,
                                    'source' => $source,
                                    'ext_plan' => $ext_plan,
                                    'project' => $project
                                ]);
                            }else if ($ext == "pdf" && $source->editable) {
                                echo $this->render('_form', [
                                    'model' => $model,
                                    'source' => $source,
                                    'ext_plan' => $ext_plan,
                                    'project' => $project
                                ]);
                            }
                        }
                        ?>
                    </div>
                </div>


            </div>
        </div>

</div>