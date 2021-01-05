<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use \backend\models\ElementType;
use \backend\models\LemmaCandExt;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
/* @var $modelLemmasCand backend\models\LemmaCandExt */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =  $model->extracted_lemma;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/dictionary','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de extracción de lemas candidatos' , 'url' => ['lemma_ext_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Lemas candidatos' , 'url' => ['lemma_ext_task/index','id_ext_plan' => $ext_plan->id_lemma_ext_plan]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$modelLemmasExtraidos = new LemmaCandExt();
$modelLemmasExtraidos = LemmaCandExt::findAll(['id_lemma'=> $model->id_lemma]);
$elementsType[] = new ElementType();
$var=0;
foreach ($modelLemmasExtraidos as $MLC):
$elementsType[$var] = ElementType::findOne(['id_element_type'=>$MLC->id_element_type]);

$var++;

endforeach;


?>


<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= Html::encode($this->title) ?></h2>
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $model->id_lemma], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id_lemma], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este lema?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="box-body">

            <div class="col-md-10">
                <table id="w1" class="table table-bordered table-striped detail-view" data-krajee-kvdetailview="kvDetailView_41d51bc2">
                            <tbody>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading ">Proyecto</h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $project->name ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading">Letra</h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $model->letter->letter ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading">Fuente</h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $model->source->name ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading">Lema extraído</h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $model->extracted_lemma ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading">Lema original</h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $model->original_lemma ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 20%; text-align: right; vertical-align: middle;">
                                    <h4 class="list-group-item-heading">Descripción Lema </h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">
                                        <p class="list-group-item-text"><?= $modelLemmasExtraidos[0]->description ?></p>
                                    </div>
                                </td>
                            </tr>

                            <?php
                            $i=0;
                            foreach ($elementsType as $este):; if(!$i==0):;?>

                                <tr>
                                <th style="width: 22%; text-align: right; vertical-align: middle;">
                                    <h4  class="list-group-item-heading"><?= $este->name  ?> </h4>
                                </th>
                                <td>
                                    <div class="kv-attribute">

                                        <p class="list-group-item-text" > <?= $modelLemmasExtraidos[$i]->description  ?> </p>
                                    </div>
                                </td>
                            </tr>

                             <?php  endif; $i++;  endforeach; ?>
                            </tbody>
                        </table>
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
    </div>
</div>


