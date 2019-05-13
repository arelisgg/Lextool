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

$this->title = 'Lemas candidatos';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de revisión de lemas candidatos' , 'url' => ['lemma_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lemma-index">

    <div id="app" class="lemma-index">

        <?php
        ActiveForm::begin([
            'id' => 'finish-form',
            'action' => Url::to(['/lemma_rev_task/finish', 'id_rev_plan' => $rev_plan->id_rev_plan]),
            'method' => 'get',
            'options' => [
                'style' => 'display:none'
            ]
        ]);
        ?>
        <?php ActiveForm::end(); ?>


        <?php Pjax::begin([
            'id' => 'lemma-rev-task-pjax',
        ]); ?>
        <?=
        GridView::widget([
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
                    'attribute'=>'agree',
                    'format' => "boolean",
                    'width'=>'95px',
                    'value'=>'agree',
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filter'=>[1=>'Sí',0=>'No'],
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
                            return Html::a('<span class="fa fa-legal"></span>', $url, ['title' => 'Ver']);
                        },
                        'update' => function ($url, $model) use ($rev_plan) {
                            $url .= '&id_rev_plan='.$rev_plan->id_rev_plan;
                            return $rev_plan->edition ?
                                Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'Editar']) : '';
                        },
                        'delete' => function ($url,$model,$key) use ($rev_plan) {
                            return $rev_plan->edition ? Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                '', [
                                    "onclick"=>"deleteLemma('$model->id_lemma','$rev_plan->id_rev_plan', '".Url::to(['/lemma_rev_task/delete',])."')",
                                    "title"=>"Eliminar"]) : '';
                        }
                    ],
                ],
            ],
            'pjaxSettings' => ['options' => ['id' => 'lemma-rev-task-pjax']],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<h3 class="panel-title"><i class="fa fa-eraser"></i> '. $this->title.'</h3>',
            ],
            'toolbar'=>[
                Html::a('<i class="fa fa-check"></i> Finalizar tarea', '', [
                    "onclick"=>"finishForm()", 'class' => 'btn btn-primary', "title"=>"Finalizar tarea"]),
                '{export}',
                '{toggleData}',

                ['content'=>

                    Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                        ['lemma_rev_task/index', 'id_rev_plan'=>$rev_plan->id_rev_plan],
                        ['class'=>'btn btn-default', 'title'=>'Reiniciar']),
                    'options' => ['class' => 'btn-group pull-right']
                ],

            ],
            'toggleDataContainer' => ['class' => 'btn-group pull-right'],
            'exportContainer' => ['class' => 'btn-group pull-right'],
            'panelBeforeTemplate' => '<div class="float-left"><div class="btn-toolbar kv-grid-toolbar" role="toolbar">{toolbar}</div></div>'

        ]);
        ?>
        <?php Pjax::end(); ?>

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
                    data: { id: id, id_rev_plan: id_rev_plan },
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