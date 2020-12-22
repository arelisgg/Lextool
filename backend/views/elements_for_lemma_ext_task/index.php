<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LexArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $letters backend\models\Letter[] */

$this->title = 'Redacción de artículos lexicográficos';
$this->params['breadcrumbs'][] = ['label' => 'Planes de redacción de artículos lexicográficos' , 'url' => ['art_red_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?= $project->id_project?></div>
<div id="name_project" class="hidden"><?= $project->name?></div>
<div class="lex-article-index">
    <?php Pjax::begin([
        'id' => 'redaction_pjax'
    ]); ?>

    <?php
    ActiveForm::begin([
        'id' => 'finish-form',
        'action' => Url::to(['/art_red_task/finish', 'id_redaction_plan' => $plan->id_redaction_plan]),
        'method' => 'get',
        'options' => [
            'style' => 'display:none'
        ]
    ]);
    ?>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
            'id' => 'redaction_grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'letter',
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
                'value'=>'lemma.extracted_lemma',

            ],
            //'article:html',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) use ($plan) {
                        $url .= '&id_redaction_plan='.$plan->id_redaction_plan;
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) use ($plan) {
                        $url .= '&id_redaction_plan='.$plan->id_redaction_plan;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'delete' => function ($url,$model,$key) use ($plan) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"deleteLexArticle('$model->id_lex_article','".Url::to(['art_red_task/delete',])."')",
                                "title"=>"Eliminar"]);
                    }
                ],
            ],
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> '. $this->title.'</h3>',
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'redaction_pjax']],
        'responsive' => true,
        'hover' => true,
        'toolbar'=>[
          /*  Html::a('<span class="fa fa-check"></span> Finalizar Tarea',
                ['art_red_task/finish', 'id_redaction_plan'=>$plan->id_redaction_plan],
                ['data-pjax' => 0, 'class' => 'btn btn-primary pull-left', 'title'=>'Finalizar Tarea']),*/
            Html::a('<i class="fa fa-check"></i> Finalizar tarea', '#', [
                "onclick"=>"event.preventDefault(); finishForm()", 'class' => 'btn btn-primary', "title"=>"Finalizar tarea"]),
            '{export}',
            '{toggleData}',

            ['content'=>

                Html::a('<span class="glyphicon glyphicon-plus"></span>',
                    ['/art_red_task/create', 'id_redaction_plan' => $plan->id_redaction_plan],
                    ['data-pjax' => 0, 'class' => 'btn btn-success', 'title'=>'Agregar']). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>',
                    ['/art_red_task/index', 'id_redaction_plan'=>$plan->id_redaction_plan],
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
    function deleteLexArticle(id,url) {
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este artículo lexicográfico?", function (result) {
            if (result) {
                $.ajax({
                    url: url,
                    type: 'get',
                    data: { id: id },
                    success:function(data){
                        console.log(data);
                        if (data === "Error"){
                            krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                        } else {
                            $.pjax.reload({container: '#redaction_pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('El artículo lexicográfico ha sido eliminada.');
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
