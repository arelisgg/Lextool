<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IllustrationLemmaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $project backend\models\Project */
/* @var $illustration_plan backend\models\IllustrationPlan */


$this->title = 'Ilustraciones de documentos';
$this->params['breadcrumbs'][] = ['label' => "Planes de ilustración" , 'url' => ['illustration/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div id="id_illustration_plan" class="hidden"><?=$illustration_plan->id_illustration_plan?></div>
<div class="illustration-document-index">

    <?php
    Modal::begin([
        'header' => '<h3 class="modelo">Ilustración</h3>',
        'id' => 'illustration',
        'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='illustrationContent'></div>";
    Modal::end();

    Modal::begin([
        'header' => '<h3 class="modelo">Editar ilustración</h3>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Pjax::begin([
        'id' => 'illustration-document-pjax'
    ]);
    ?>

    <?= GridView::widget([
        'id'=> 'illustration-lemma-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'document_search',
                'value' =>  'document.docType.name',
                'group' => true,
            ],

            [
                'attribute' => 'archivo',
                'format' => 'raw',
                'value'=>function ($model, $index, $widget) {
                    return Html::button('Ilustración', ["onclick"=>"illustration('".$model->id_illustration."')", "title"=>"Ver Ilustración", 'class' => 'btn btn-link']);
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '#', [
                                "onclick"=>"actionUpdate('$model->id_illustration_document', '".Url::to(['/illustration_document/update',])."')",
                                "title"=>"Editar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '#', [
                                "onclick"=>"illustrationView('$model->id_illustration_document', '".Url::to(['/illustration_document/view',])."')",
                                "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '#', [
                                "onclick"=>"actionDelete('$model->id_illustration_document', '".Url::to(['/illustration_document/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'illustration-document-pjax']],
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-file-image-o"></i> '. $this->title.'</h3>',
        ],
        'toolbar'=>[
        Html::a('<span class="fa fa-check"></span> Finalizar tarea',
            ['illustration/finish', 'id_illustration_plan'=>$illustration_plan->id_illustration_plan],
            ['data-pjax' => 0, 'class' => 'btn btn-primary pull-left', 'title'=>'Finalizar Tarea']),
        '{export}',
        '{toggleData}',

        ['content'=>

            Html::a('<span class="glyphicon glyphicon-plus"></span>',
                ['illustration_document/create', 'id_illustration_plan'=>$illustration_plan->id_illustration_plan],
                ['data-pjax' => 0, 'class' => 'btn btn-success', 'title'=>'Agregar']). ' '.
            Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                ['illustration_document/index', 'id_illustration_plan'=>$illustration_plan->id_illustration_plan],
                ['data-pjax' => 0, 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            'options' => ['class' => 'btn-group pull-right']
        ],
    ],
    'toggleDataContainer' => ['class' => 'btn-group pull-right'],
    'exportContainer' => ['class' => 'btn-group pull-right'],
    'panelBeforeTemplate' => '<div class="float-left"><div class="btn-toolbar kv-grid-toolbar" role="toolbar">{toolbar}</div></div>'
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    function illustration(id){
        $.ajax({
            url: 'illustration',
            type: 'Get',
            data: {id:id},
            success:function(data){
                $('#illustration').modal('show').find('#illustrationContent').html(data);
            },
            fail: function(){alert("error")}
        });
    }
    function illustrationView(id){
        $.ajax({
            url: 'view',
            type: 'Get',
            data: {id:id},
            success:function(data){
                $('#illustration').modal('show').find('#illustrationContent').html(data);
            },
            fail: function(){alert("error")}
        });
    }
    function actionDelete(id, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id='+id,
                    type: 'POST',
                    success:function(data){
                        if (data == "Error"){
                            krajeeDialogError.alert("No se ha podido eliminadar, ha ocurrido un error.");
                        } else {
                            $.pjax.reload({container: '#illustration-document-pjax'});
                            $(document).find('#illustration').modal('hide');
                            krajeeDialogSuccess.alert('La ilutración asignada a este documento ha sido eliminada.');
                        }
                    },
                    fail: function(){
                        krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                    }
                });
            }
        });
    }
</script>