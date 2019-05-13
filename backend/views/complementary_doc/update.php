<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ComplementaryDoc */

$this->title = 'Aprobar documento: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Documentos complementarios', 'url' => ['index', 'id_project' =>$project->id_project]];
$this->params['breadcrumbs'][] = 'Aprobar';
?>

<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="complementary-doc-view">


    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-legal"></i> <?= $this->title ?></h2>
            <div class="pull-right">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'accepted', ['options' => ['class' => 'hidden']])->textInput() ?>
                <?= Html::submitButton($model->accepted == 0 ? '<i class="fa fa-check"></i> Aprobar documento' : '<i class="fa fa-close"></i> Desaprobar documento', [
                        'class' => $model->accepted == 0 ? 'btn btn-primary btn-sm' : 'btn btn-danger btn-sm']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $project->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Tipo de Documento</h4>
                            <p class="list-group-item-text"><?= $model->docType->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Aprobado</h4>
                            <p class="list-group-item-text"><?= $model->accepted == 0 ?  "No":  "Si"?></p>
                        </li>

                    </ul>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3><strong>Vista Previa del Documento</strong></h3>
                    <iframe id="preview" src="../../web/js/pdfjs/web/viewer.html?file=../../../uploads/project/complementary_doc/<?= $model->url?>" width="100%" height="500" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

</div>
