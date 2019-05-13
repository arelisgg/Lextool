<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ComplementaryDoc */
/* @var $project backend\models\Project */

$this->title = $model->docType->name;
$this->params['breadcrumbs'][] = ['label' => 'Planes de confección de documentos complementarios' , 'url' => ['document_make/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Documentos complementarios', 'url' => ['document_make/index', 'id_doc_make_plan' =>$id_doc_make_plan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>


<div class="complementary-doc-view">


    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="glyphicon glyphicon-book"></i> <?= $this->title ?></h2>
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $model->id_complementary_doc, 'id_doc_make_plan' =>$id_doc_make_plan],
                    ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id_complementary_doc, 'id_doc_make_plan' =>$id_doc_make_plan], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => '¿Está seguro de eliminar este elemento?',
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
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $project->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Tipo de Documento</h4>
                            <p class="list-group-item-text"><?= $model->docType->name ?></p>
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