<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Componentes';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-index">

    <?php Pjax::begin([
        'id' => 'sub_model_pjax'
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'name',
                'value' =>  'name',
            ],
            [
                'attribute'=>'repeat',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'repeat',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'required',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'required',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'used',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'used',
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
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_sub_model', '".Url::to(['/sub_model/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ]
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'sub_model_pjax']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-file-archive-o"></i> '. $this->title.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/sub_model/create','id_project' => $project->id_project]), [
                    'class' => 'btn btn-success',
                    "title"=>"Agregar Sub Modelo"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_project='.$project->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
        ],
        'responsive' => true,
        'hover' => true
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    function actionDelete(id, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este componente?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id='+id,
                    type: 'POST',
                    success:function(data){
                        if (data == "Error"){
                            krajeeDialogError.alert("No se ha podido eliminar el componente porque ya ha sido asociado al modelo de artículo.");
                        } else {
                            $.pjax.reload({container: '#sub_model_pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('El componente ha sido eliminado.');
                        }
                    },
                    fail: function(){
                        krajeeDialogError.alert("Ha ocurrido un error.");
                    }
                });
            }
        });
    }

</script>


