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
$this->params['breadcrumbs'][] = ['label' => 'Planes de extracción de documentos complementarios' , 'url' => ['document_ext_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="document-index">

    <?php Pjax::begin([
        'id' => 'lemma_pjax'
    ]); ?>

    <?php
    ActiveForm::begin([
        'id' => 'finish-form',
        'action' => Url::to(['/document_ext_task/finish', 'id_doc_ext_plan' => $ext_plan->id_doc_ext_plan]),
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
            /*[
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
            ],*/

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) use ($ext_plan) {
                        $url .= '&id_ext_plan='.$ext_plan->id_doc_ext_plan;
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url,["title" => "Ver"]);
                    },
                ],
            ],
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> '. $this->title.'</h3>',
        ],
        'responsive' => true,
        'hover' => true,
        'toolbar'=>[
            Html::a('<i class="fa fa-check"></i> Finalizar tarea', '', [
                "onclick"=>"finishForm()", 'class' => 'btn btn-primary', "title"=>"Finalizar Tarea"]),
            '{export}',
            '{toggleData}',

            ['content'=>

                Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    '#',
                    ['onclick'=>"loadSourceDocument('".Url::to(['/document_ext_task/sources', 'id_doc_ext_plan' => $ext_plan->id_doc_ext_plan])."')",
                        'class' => 'btn btn-success', 'title'=>'Agregar']). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                    ['document_ext_task/index', 'id_ext_plan'=>$ext_plan->id_doc_ext_plan],
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

<div id="myModal" class="fade modal" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 style="text-transform: uppercase; font-weight: 600;">Vista previa de la fuente</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'action' => 'create'
                ]); ?>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cerrar</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" id="extractLemma" class="btn btn-primary btn-block">Continuar</button>
                    </div>
                </div>

                <div id="modalContent"></div>

                <div class="modal-footer">
                    <input type="hidden" name="id_project" value="<?= $project->id_project ?>">
                    <input type="hidden" name="id_source" value="<?= $source->id_source ?>">
                    <input type="hidden" name="id_ext_plan" value="<?= $ext_plan->id_doc_ext_plan ?>">
                </div>

                <?php ActiveForm::end()?>
            </div>
        </div>
    </div>
</div>


<script>
    function finishForm() {
        krajeeDialogWarning.confirm("¿Está seguro de finalizar esta tarea?", function (result) {
            if (result) {
                $("#finish-form").submit();
            }
        });
    }
</script>