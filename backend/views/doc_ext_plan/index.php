<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DocExtPlanSearch */
/* @var $model backend\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan de extracción de documentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="doc-ext-plan-index">

    <?php
    Modal::begin([
        'header' => '<h3 id=8 class="modelo"></h3>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Pjax::begin([
        'id' => 'doc-ext-plan-pjax',
        //'timeout' => 10000,
    ]);
    ?>

    <?= GridView::widget([
        'id' => 'doc-ext-plan-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'usuario',
                'value' =>  'user.full_name',
                //'group' => true,
            ],
            [
                'attribute'=>'doc_name',
                'value' =>  'docType.name',
            ],
            [
                'attribute'=>'source_name',
                'value' =>  'source.name',
            ],
            'start_date',
            'end_date',
            [
                'attribute'=>'finished',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'finished',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'late_search',
                'width'=>'95px',
                'value'=>'late',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['Sí'=>'Sí','No'=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '', [
                                "onclick"=>"actionUpdate('$model->id_doc_ext_plan', '".Url::to(['/doc_ext_plan/update',])."')",
                                "title"=>"Editar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '', [
                                "onclick"=>"actionView('$model->id_doc_ext_plan', '".Url::to(['/doc_ext_plan/view',])."')",
                                "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_doc_ext_plan', '".Url::to(['/doc_ext_plan/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'doc-ext-plan-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-calendar"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'rowOptions' => function($model, $index, $widget, $grid){
            if($model->late == 'Sí'){
                return ['class'=>'danger'];
            }
        },
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', [
                    "onclick"=>"actionCreate('$model->id_project','".Url::to(['/doc_ext_plan/create',])."')",
                    'class' => 'btn btn-success',
                    "title"=>"Agregar"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_project='.$model->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    function actionDelete(id, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id='+id,
                    type: 'POST',
                    success:function(data){
                        if (data == "Error"){
                            krajeeDialogError.alert("No se ha podido eliminadar, ha ocurrido un error.");
                        } else if (data == "Ok"){
                            $.pjax.reload({container: '#doc-ext-plan-pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('La tarea de extracción ha sido eliminada.');
                        }  else if (data == "Used"){
                            krajeeDialogError.alert('No se ha podido eliminar, la tarea de extracción tiene asociado documentos extraidos o planes de revisión de documentos.');
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