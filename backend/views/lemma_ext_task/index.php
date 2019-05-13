<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\LemmaSearch */
/* @var $currentLetter backend\models\Lemma */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $letters backend\models\Letter[] */
/* @var $project backend\models\Project */
/* @var $ext_plan backend\models\LemmaExtPlan */

$this->title = 'Lemas candidatos';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de extracción de lemas candidatos' , 'url' => ['lemma_ext_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-index">

    <?php
    ActiveForm::begin([
        'id' => 'finish-form',
        'action' => Url::to(['/lemma_ext_task/finish', 'id_lemma_ext_plan' => $ext_plan->id_lemma_ext_plan]),
        'method' => 'get',
        'options' => [
            'style' => 'display:none'
        ]
    ]);
    ?>
    <?php ActiveForm::end(); ?>

    <?php Pjax::begin([
        'id' => 'lemma_pjax'
    ]); ?>
    <?= GridView::widget([
        'id'=> 'lemma-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'id_letter',
                'width'=>'95px',
                'value'=>'letter.letter',
                'group' => true,
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=> \yii\helpers\ArrayHelper::map($letters,'id_letter', 'letter'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            'extracted_lemma',
            'original_lemma',
            'substructure',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> '. $this->title.'</h3>',
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'lemma_pjax']],
        'responsive' => true,
        'hover' => true,
        'toolbar'=>[
            Html::a('<i class="fa fa-check"></i> Finalizar tarea', '', [
                "onclick"=>"finishForm()", 'class' => 'btn btn-primary', "title"=>"Finalizar tarea"]),
            '{export}',
            '{toggleData}',

            ['content'=>

                Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    '#',
                    ['onclick'=>"loadSourcesLemma('".Url::to(['/lemma_ext_task/sources', 'id_lemma_ext_plan' => $ext_plan->id_lemma_ext_plan])."')",
                        'class' => 'btn btn-success', 'title'=>'Agregar']). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                    ['lemma_ext_task/index', 'id_ext_plan'=>$ext_plan->id_lemma_ext_plan],
                    ['class'=>'btn btn-default', 'title'=>'Reiniciar']),
                'options' => ['class' => 'btn-group pull-right']
            ],

        ],
        'toggleDataContainer' => ['class' => 'btn-group pull-right'],
        'exportContainer' => ['class' => 'btn-group pull-right'],
        'panelBeforeTemplate' => '<div class="float-left"><div class="btn-toolbar kv-grid-toolbar" role="toolbar">{toolbar}</div></div>'

    ]); ?>

    <?php Pjax::end(); ?>



    <div id="myModal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 style="text-transform: uppercase; font-weight: 600;">Seleccionar la fuente</h3>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'action' => 'create'
                    ]); ?>

                    <div id="modalContent"></div>

                    <div class="modal-footer">
                        <input id="current-letter" type="hidden" name="id_letter" value="<?= $currentLetter->id_letter ?>" >
                        <input type="hidden" name="id_project" value="<?= $project->id_project ?>">
                        <input type="hidden" id="ext_plan" name="id_ext_plan" value="<?= $ext_plan->id_lemma_ext_plan ?>">
                    </div>

                    <?php ActiveForm::end()?>
                </div>
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

    function deleteLemma(id,id_rev_plan,url) {
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este lema?", function (result) {
            if (result) {
                $.ajax({
                    url: url,
                    type: 'get',
                    data: { id: id, id_ext_plan: id_ext_plan },
                    success:function(data){
                        console.log(data);
                        if (data === "Error"){
                            krajeeDialogError.alert("No se ha podido eliminadar, ha ocurrido un error.");
                        } else {
                            $.pjax.reload({container: '#lemma-rev-task-pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('La tarea de revisión ha sido eliminada.');
                        }
                    },
                    fail: function(){
                        krajeeDialogError.alert("No se ha podido eliminadar, ha ocurrido un error.");
                    }
                })
            }
        });
    }
</script>