<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos complementarios';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de Revisión de Documentos Complementarios' , 'url' => ['document_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="document-index">

    <?php Pjax::begin([
        'id' => 'document_pjax'
    ]); ?>

    <?php
    ActiveForm::begin([
        'id' => 'finish-form',
        'action' => Url::to(['/document_rev_task/finish', 'id_rev_plan' => $rev_plan->id_rev_plan]),
        'method' => 'get',
        'options' => [
            'style' => 'display:none'
        ]
    ]);
    ?>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'doc_type',
                'value' =>  'docType.name',
            ],
            [
                'attribute'=>'reviewed',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'reviewed',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[ 1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) use ($rev_plan) {
                        $url .= '&id_rev_plan='.$rev_plan->id_rev_plan;
                        return Html::a('<span class="fa fa-legal"></span>', $url, ["title"=>"Ver"]);
                    },
                    'update' => function ($url, $model) use ($rev_plan) {
                        $url .= '&id_rev_plan='.$rev_plan->id_rev_plan;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ["title"=>"Editar"]);
                    },
                    'delete' => function ($url,$model,$key) use ($rev_plan) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"deleteDoc('$model->id_document','$rev_plan->id_rev_plan', '".Url::to(['/document_rev_task/delete',])."')",
                                "title"=>"Eliminar"]);
                    }
                ],
            ],
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-eraser"></i> '. $this->title.'</h3>',
        ],
        'responsive' => true,
        'hover' => true,
        'toolbar'=>[
            Html::a('<i class="fa fa-check"></i> Finalizar tarea', '', [
                "onclick"=>"finishForm()", 'class' => 'btn btn-primary', "title"=>"Finalizar Tarea"]),
            '{export}',
            '{toggleData}',

            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                    ['document_rev_task/index', 'id_rev_plan'=>$rev_plan->id_rev_plan],
                    ['class'=>'btn btn-default', 'title'=>'Reiniciar']),
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
    function finishForm() {
        krajeeDialogWarning.confirm("¿Está seguro de finalizar esta tarea?", function (result) {
            if (result) {
                $("#finish-form").submit();
            }
        });
    }

    function deleteDoc(id,id_rev_plan,url) {
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este documento?", function (result) {
            if (result) {
                $.ajax({
                    url: url,
                    type: 'get',
                    data: { id: id, id_rev_plan: id_rev_plan },
                })
            }
        });
    }
</script>

