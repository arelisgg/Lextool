<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SourceSearch */
/* @var $model backend\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fuentes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div id="source-index" class="source-index">

    <?php
    Modal::begin([
        'header' => '<h2 id=5 class="modelo"></h2>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Pjax::begin([
        'id' => 'source-pjax',
        //'timeout' => 10000,
    ]);
    ?>

    <?= GridView::widget([
        'id' => 'source-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '', [
                                "onclick"=>"actionUpdate('$model->id_source', '".Url::to(['/source/update',])."')",
                                "title"=>"Editar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '', [
                                "onclick"=>"actionView('$model->id_source', '".Url::to(['/source/view',])."')",
                                "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_source', '".Url::to(['/source/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'source-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-file-text"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', [
                    "onclick"=>"actionCreate('$model->id_project','".Url::to(['/source/create',])."')",
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
                            krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                        } else if (data == "Used"){
                            krajeeDialogError.alert("No se ha podido eliminar, la fuente tiene planes de extracción de lemas o documentos asociados.");
                        } else {
                            $.pjax.reload({container: '#source-pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('La fuente ha sido eliminada.');
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