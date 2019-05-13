<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModelDocument backend\models\DocumentSearch */
/* @var $project backend\models\Project */
/* @var $plan backend\models\DocMakePlan */
/* @var $dataProviderDocument yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\ComplementaryDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos complementarios';
$this->params['breadcrumbs'][] = ['label' => 'Planes de confección de documentos complementarios' , 'url' => ['document_make/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="document-make-index">

    <div class="col-lg-6">
        <?php Pjax::begin([
            'id' => 'document-export-pjax'
        ]); ?>

        <?= GridView::widget([
            'id' => 'document-export-grid',
            'dataProvider' => $dataProviderDocument,
            'filterModel' => $searchModelDocument,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute'=>'doc_type',
                    'value' =>  'docType.name',
                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url,$model,$key) {
                            return Html::a('<span class="glyphicon glyphicon-download-alt"></span>',
                                ['document_make/export', 'id_document' => $model->id_document], ['title' => 'Exportar', 'data-pjax' => 0,]);
                        },
                    ]
                ],
            ],
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'document-export-pjax']],
            'responsive'=> true,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<h3 class="panel-title"><i class="fa fa-download"></i> Exportar Documentos</h3>',
            ],
            'toolbar'=>[
                'options' => ['class' => 'pull-left'],
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_doc_make_plan='.$plan->id_doc_make_plan, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
                ],
                '{toggleData}',
                '{export}',
            ],

        ]); ?>

        <?php Pjax::end(); ?>
    </div>

    <div class="col-lg-6">
        <?php Pjax::begin([
            'id' => 'document-import-pjax',
        ]); ?>

        <?= GridView::widget([
            'id' => 'document-import-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],

                [
                    'attribute'=>'doc_name',
                    'value' =>  'docType.name',
                ],
                //'name',

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url,$model,$key) use($plan){
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                ['document_make/view', 'id' => $model->id_complementary_doc, 'id_doc_make_plan' => $plan->id_doc_make_plan], [
                                    'data-pjax' => 0,
                                    "title"=>"Ver"]);
                        },
                        'update' => function ($url,$model,$key) use($plan){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                ['document_make/update', 'id' => $model->id_complementary_doc, 'id_doc_make_plan' => $plan->id_doc_make_plan], [
                                    'data-pjax' => 0,
                                    "title"=>"Editar"]);
                        },
                        'delete' => function ($url,$model,$key) use($plan) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id_complementary_doc, 'id_doc_make_plan' =>$plan->id_doc_make_plan], [
                                'data' => [
                                    'confirm' => '¿Está seguro de eliminar este elemento?',
                                    'method' => 'post',
                                ],
                                'data-pjax' => 1,
                            ]);
                        },
                    ],
                ],
            ],
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'document-import-pjax']],
            'responsive'=> true,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<h3 class="panel-title"><i class="fa fa-upload"></i> Importar Documentos</h3>',
            ],
            'toolbar'=> [
                Html::a('<span class="fa fa-check"></span> Finalizar Tarea',
                    ['document_make/finish', 'id_doc_make_plan'=>$plan->id_doc_make_plan],
                    ['data-pjax' => 0, 'class' => 'btn btn-primary pull-left', 'title'=>'Finalizar Tarea']),
                '{export}',
                '{toggleData}',
                ['content'=>
                    Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create?id_doc_make_plan='.$plan->id_doc_make_plan, [
                        'data-pjax' => 0,
                        'class' => 'btn btn-success',
                        "title"=>"Agregar"]). ' '.
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_doc_make_plan='.$plan->id_doc_make_plan, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
                    'options' => ['class' => 'btn-group pull-right']
                ],
            ],
            'toggleDataContainer' => ['class' => 'btn-group pull-right'],
            'exportContainer' => ['class' => 'btn-group pull-right'],
            'panelBeforeTemplate' => '<div class="float-left"><div class="btn-toolbar kv-grid-toolbar" role="toolbar">{toolbar}</div></div>'
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

</div>
