<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
/* @var $modelLemmasCand backend\models\LemmaCandExt */



$this->title = 'Extraer lema candidato';

//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de extracción de lemas candidatos' , 'url' => ['lemma_ext_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Lemas candidatos' , 'url' => ['lemma_ext_task/index','id_ext_plan' => $ext_plan->id_lemma_ext_plan]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-create">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title?></h2>
            </div>
            <div class="box-body">
                <?php
                if (count($ext_plan->semanticFields) > 0){
                    echo  '<div class="row">
                <div class="col-md-12">
                 <h3>Campos Semánticos</h3>
                 <ul class="list-unstyled">';
                    foreach ($ext_plan->semanticFields as $semanticField){
                        echo '<li><i class="fa fa-check"></i> '.$semanticField->name.'</li>';
                    }
                    echo '</ul> </div></div>';
                }
                ?>

                <?php
                foreach ($extension as $ext) {
                    if ($ext == "pdf" && !$source->editable){
                        echo $this->render('_form-pdf', [
                            'model' => $model,
                            'modelLemmasCand'=> $modelLemmasCand,
                            'source' => $source,
                            'ext_plan' => $ext_plan,
                            'letter' => $letter,
                            'project' => $project,
                            'elements' => $elements,


                        ]);
                    }elseif ($ext == "jpg" ||
                        $ext == "jpeg" ||
                        $ext == "png") {
                        echo $this->render('_form-image', [
                            'model' => $model,
                            'modelLemmasCand'=> $modelLemmasCand,
                            'source' => $source,
                            'ext_plan' => $ext_plan,
                            'letter' => $letter,
                            'project' => $project,
                            'elements' => $elements,


                        ]);
                    }elseif($ext == "pdf" && $source->editable) {
                        echo $this->render('_form', [
                            'model' => $model,
                            'modelLemmasCand'=> $modelLemmasCand,
                            'source' => $source,
                            'ext_plan' => $ext_plan,
                            'letter' => $letter,
                            'project' => $project,
                            'elements' => $elements,


                        ]);
                    }
                }
                ?>
            </div>
        </div>
</div>
