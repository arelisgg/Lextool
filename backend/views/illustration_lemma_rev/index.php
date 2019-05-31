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
/* @var $illustration_rev_plan backend\models\IllustrationRevPlan */
/* @var $letters backend\models\Letter[] */


$this->title = 'Ilustraciones de lemas';
$this->params['breadcrumbs'][] = ['label' => "Planes de revisión de ilustración" , 'url' => ['illustration/revplans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div id="id_illustration_rev_plan" class="hidden"><?=$illustration_rev_plan->id_illustration_rev_plan?></div>
<div id="app" class="illustration-lemma-index">

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
        'header' => '<h3 class="modelo">Revisar ilustración</h3>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    ?>


                    <?php
                    Pjax::begin([
                        'id' => 'illustration-lemma-pjax'
                    ]);
                    ?>


                        <?= GridView::widget([
                            'id'=> 'illustration-lemma-grid',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],

                                [
                                    'attribute'=>'id_letter',
                                    //'format' => "boolean",
                                    'width'=>'95px',
                                    'value'=>'lemma.letter.letter',
                                    'group' => true,
                                    'filterType'=>GridView::FILTER_SELECT2,
                                    'filter'=> \yii\helpers\ArrayHelper::map($letters,'id_letter', 'letter'),
                                    'filterWidgetOptions'=>[
                                        'pluginOptions'=>['allowClear'=>true],
                                    ],
                                    'filterInputOptions'=>['placeholder'=>''],
                                ],

                                [
                                    'attribute'=>'lemma_search',
                                    'value' =>  'lemma.extracted_lemma',
                                    'group' => true,
                                ],

                                [
                                    'attribute' => 'archivo',
                                    'format' => 'raw',
                                    'value'=>function ($model, $index, $widget) {
                                        return Html::button('Ilustración', ["onclick"=>"illustration('".$model->id_illustration."')", "title"=>"Ver Ilustración", 'class' => 'btn btn-link', 'style' => "padding: 0px"]);
                                    }
                                ],
                                [
                                    'attribute'=>'reviewed',
                                    'format' => "boolean",
                                    'width'=>'95px',
                                    'value'=>'reviewed',
                                    'filterType'=>GridView::FILTER_SELECT2,
                                    'filter'=>[1=>'Si',0=>'No'],
                                    'filterWidgetOptions'=>[
                                        'pluginOptions'=>['allowClear'=>true],
                                    ],
                                    'filterInputOptions'=>['placeholder'=>''],
                                ],
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'template' => '{update}',
                                    'buttons' => [
                                        'update' => function ($url,$model,$key) {
                                            return Html::a('<span class="fa fa-legal"></span>',
                                                '#', [
                                                    "onclick"=>"actionUpdate('$model->id_illustration_lemma', '".Url::to(['/illustration_lemma_rev/update',])."')",
                                                    "title"=>"Revisar"]);
                                        },
                                        /*'view' => function ($url,$model,$key) {
                                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                                '#', [
                                                    "onclick"=>"illustrationView('$model->id_illustration_lemma', '".Url::to(['/illustration_lemma_rev/view',])."')",
                                                    "title"=>"Ver"]);
                                        },
                                        'delete' => function ($url,$model,$key) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                                '#', [
                                                    "onclick"=>"actionDelete('$model->id_illustration_lemma', '".Url::to(['/illustration_lemma_rev/delete',])."')",
                                                    "title"=>"Eliminar"]);
                                        },*/
                                    ],
                                ],
                            ],
                            'pjax' => true,
                            'pjaxSettings' => ['options' => ['id' => 'illustration-lemma-pjax']],
                            'responsive' => true,
                            'hover' => true,
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<h3 class="panel-title"><i class="fa fa-file-image-o"></i> '. $this->title.'</h3>',
                            ],
                            'toolbar'=>[
                                Html::a('<span class="fa fa-check"></span> Finalizar tarea',
                                        ['illustration/revfinish', 'id_illustration_rev_plan'=>$illustration_rev_plan->id_illustration_rev_plan],
                                        ['data-pjax' => 0, 'class' => 'btn btn-primary pull-left', 'title'=>'Finalizar Tarea']),
                                '{export}',
                                '{toggleData}',

                                ['content'=>
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                                        ['illustration_lemma_rev/index', 'id_illustration_rev_plan'=>$illustration_rev_plan->id_illustration_rev_plan],
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
                            $.pjax.reload({container: '#illustration-lemma-pjax'});
                            $(document).find('#illustration').modal('hide');
                            krajeeDialogSuccess.alert('La ilutración asignada a este lema ha sido eliminada.');
                        }
                    },
                    fail: function(){
                        krajeeDialogError.alert("No se ha podido eliminadar, ha ocurrido un error.");
                    }
                });
            }
        });
    }
</script>