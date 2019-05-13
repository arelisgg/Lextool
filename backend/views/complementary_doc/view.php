<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ComplementaryDoc */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Complementary Docs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="complementary-doc-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="glyphicon glyphicon-book"></i> <?= $this->title ?></h2>
        </div>
        <div class="box-body">
            <p>
                <?= Html::a('Aprobar Documento', ['update', 'id' => $model->id_complementary_doc], ['class' => 'btn btn-primary']) ?>
            </p>
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
                            <h4 class="list-group-item-heading ">Nombre</h4>
                            <p class="list-group-item-text"><?= $model->name ?></p>
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
