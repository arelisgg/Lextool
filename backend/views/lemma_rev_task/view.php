<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */

$this->title = $model->original_lemma;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de revisión de lemas candidatos' , 'url' => ['lemma_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Lemas candidatos' , 'url' => ['lemma_rev_task/index','id_rev_plan' => $rev_plan->id_rev_plan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title" style="text-transform: capitalize"><i class="fa fa-language"></i> <?= $this->title ?></h2>
            <div class="pull-right">
            <?php


            if (!$model->agree) {
                echo Html::a('<i class="fa fa-check"></i> Aprobar lema',
                    ['aprove', 'id' => $model->id_lemma, 'id_rev_plan' => $rev_plan->id_rev_plan],
                    ['class' => ' btn btn-primary btn-sm']). " ";
            }else {
                echo Html::a('<i class="fa fa-close"></i> Desaprobar lema',
                        ['aprove', 'id' => $model->id_lemma, 'id_rev_plan' => $rev_plan->id_rev_plan],
                        ['class' => ' btn btn-danger btn-sm']). " ";
            }

            if ($rev_plan->edition) {

                echo Html::a('<i class="glyphicon glyphicon-pencil"></i> ',
                    ['update', 'id' => $model->id_lemma, 'id_rev_plan' => $rev_plan->id_rev_plan],
                    ['class' => 'btn btn-primary btn-sm'])." ".
                    Html::a('<i class="glyphicon glyphicon-trash"></i> ', ['view-delete', 'id' => $model->id_lemma, 'id_rev_plan' => $rev_plan->id_rev_plan], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar este lema?',
                            'method' => 'post',
                        ],
                    ]);

            }
            ?>
            </div>
        </div>
        <div class="box-body">

            <?php

            ?>

            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Aprobado</h4>
                            <p class="list-group-item-text"><?= $model->agree ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $project->name ?></p>
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
                            <h4 class="list-group-item-heading">Lema extraído</h4>
                            <p class="list-group-item-text"><?= $model->extracted_lemma ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Lema original</h4>
                            <p class="list-group-item-text"><?= $model->original_lemma ?></p>
                        </li>
                        <?php
                        if ($model->substructure != "" && $model->substructure != null) {
                            echo  '<li class="list-group-item">
                    <h4 class="list-group-item-heading">Elemento lexicográfico</h4>
                    <p class="list-group-item-text">'.$model->substructure.'</p>
                     </li>';
                        }
                        ?>
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
                            <form action="delete-remark" method="post">
                               <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->getRequest()->csrfToken ?>">
                                 <input type="hidden" name="id_rev_plan" value="'.$rev_plan->id_rev_plan.'">
                                <input type="hidden" name="id_lemma" value="'.$model->id_lemma.'">
                              <button class="margin-top-10 btn btn-danger">Eliminar observación</button>
                            </form>
                        </li>';
                        }else {
                            echo '
                               <li class="list-group-item">
                            <form action="remark" method="post">
                               <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->getRequest()->csrfToken ?>">
                                <h4 class="list-group-item-heading margin-bottom-10">Observaciones: </h4>
                                <textarea class="form-control" name="remark" rows="6"></textarea>
                                <input type="hidden" name="id_rev_plan" value="'.$rev_plan->id_rev_plan.'">
                                <input type="hidden" name="id_lemma" value="'.$model->id_lemma.'">
                                <button type="submit" class="margin-top-10 btn btn-success">Añadir observación</button>
                            </form>
                        </li>
                            ';
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
