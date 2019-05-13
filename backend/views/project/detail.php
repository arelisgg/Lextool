<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use backend\models\UserProject;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="project-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-book"></i> <?= $this->title?></h2>
            <p class="pull-right" style="margin: 0">
                <?php
                echo \common\models\User::userCanProjectAndRol($model->id_project, "Jefe de Proyecto") ?
                    Html::a('<i class="glyphicon glyphicon-pencil"></i>',
                        ['update', 'id' => $model->id_project],
                        ['class' => 'btn btn-primary btn-sm', 'title' => 'Editar'])." ".
                    Html::button('<i class="glyphicon glyphicon-trash"></i>',[
                        "onclick"=>"actionDelete('$model->id_project', '".Url::to(['/project/delete_view',])."')",
                        "title"=>"Eliminar", 'class' => 'btn btn-danger btn-sm'])
                    :"";
                ?>
            </p>
        </div>
        <div class="box-body" style="margin: 10px">

            <div class="row">
                <div class="col-lg-3">
                    <div id="foto" style="margin-top: 10px; box-shadow: 0px 0px 5px; height: 270px; width: 235px">
                        <img src="<?=$model->getImageUrl() ?>" height="100%" width="100%">
                    </div>
                </div>

                <div class="col-lg-9">
                    <h3> <?= $model->name ?> </h3>
                    <p><strong>Tipo de diccionario:</strong> <?= $model->dictionaryType->type ?></p>
                    <p><strong>Estado:</strong> <?= $model->status?></p>
                    <p><strong>Jefe de proyecto:</strong> <?= UserProject::findOne(['id_project' => $model->id_project, 'role' => 'Jefe de Proyecto'])->user->full_name ?></p>
                    <p><strong>Planta lexicográfica:</strong> <a href="<?= $model->getPlantUrl() ?>">Descargar</a></p>
                    <p><strong>Fecha inicio: </strong><?= $model->start_date ?></p>
                    <p><strong>Fecha fin: </strong> <?= $model->end_date ?></p>
                    <p><strong>Descripción: </strong> <?= $model->description ?></p>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a name="tab-1" href="#tab-1" data-toggle="tab">Equipo</a></li>
                            <li><a name="tab-2" href="#tab-2" data-toggle="tab">Fuentes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab-1">

                                <?php
                                Pjax::begin([
                                'id' => 'user-project-pjax',
                                //'timeout' => 10000,
                                ]);
                                ?>

                                <?= GridView::widget([
                                    'id' => 'user-project-grid',
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'kartik\grid\SerialColumn'],
                                        [
                                            'attribute'=>'usuario',
                                            'value' =>  'user.username',
                                            'group' => true,
                                        ],
                                        'role',
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => ['options' => ['id' => 'user-project-pjax']],
                                    'responsive'=>true,
                                    'panel' => [
                                        'type' => GridView::TYPE_PRIMARY,
                                        'heading' => '<h3 class="panel-title"><i class="fa fa-group"></i> Equipo del '. $model->name.'</h3>',
                                    ],
                                    'toolbar'=>[
                                        'options' => ['class' => 'pull-left'],
                                        ['content'=>

                                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'view?id='.$model->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
                                        ],
                                        '{toggleData}',
                                        '{export}',
                                    ],
                                ]); ?>
                                <?php Pjax::end(); ?>


                            </div>
                            <div class="tab-pane fade" id="tab-2">
                                <?php
                                Pjax::begin([
                                'id' => 'source-pjax',
                                //'timeout' => 10000,
                                ]);
                                ?>

                                <?= GridView::widget([
                                    'id' => 'source-grid',
                                    'dataProvider' => $dataProviderSource,
                                    'filterModel' => $searchModelSource,
                                    'columns' => [
                                        ['class' => 'kartik\grid\SerialColumn'],
                                        'name',
                                        [
                                            'attribute' => 'url',
                                            'format' => 'raw',
                                            'value'=>function ($model, $index, $widget) {
                                                return $model->url != 'null' ? '<a data-pjax=0 href="' . $model->getSourceUrl() . '">Descargar fichero</a>' : $model->getSourceUrl();
                                            }
                                        ],
                                        [
                                            'attribute'=>'editable',
                                            'format' => "boolean",
                                            'width'=>'95px',
                                            'value'=>'editable',
                                            'filterType'=>GridView::FILTER_SELECT2,
                                            'filter'=>[1=>'Sí',0=>'No'],
                                            'filterWidgetOptions'=>[
                                                'pluginOptions'=>['allowClear'=>true],
                                            ],
                                            'filterInputOptions'=>['placeholder'=>''],
                                        ],
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => ['options' => ['id' => 'source-pjax']],
                                    'responsive'=>true,
                                    'panel' => [
                                        'type' => GridView::TYPE_PRIMARY,
                                        'heading' => '<h3 class="panel-title"><i class="fa fa-file-text"></i> Fuentes del '. $model->name.'</h3>',
                                    ],
                                    'toolbar'=>[
                                        'options' => ['class' => 'pull-left'],
                                        ['content'=>
                                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'view?id='.$model->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
                                        ],
                                        '{toggleData}',
                                        '{export}',
                                    ],
                                ]); ?>
                                <?php Pjax::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>





        </div>
    </div>



</div>
