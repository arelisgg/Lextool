<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserProjectSearch */
/* @var $model backend\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equipo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div id="user-project-index" class="user-project-index">

    <?php
    Modal::begin([
        'header' => '<h2 id=2 class="modelo"></h2>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Pjax::begin([
        'id' => 'user-project-pjax',
        //'timeout' => 10000,
    ]);
    ?>

    <?= GridView::widget([
        'id' => 'user-project-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'usuario',
                'value' =>  'user.full_name',
                'group' => true,
            ],
            'role',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '', [
                                "onclick"=>"action('$model->id_project', '$model->id_user', '$model->role', '".Url::to(['/user_project/update',])."', 'update')",
                                "title"=>"Editar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '', [
                                "onclick"=>"action('$model->id_project', '$model->id_user', '$model->role', '".Url::to(['/user_project/view',])."', 'view')",
                                "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_project', '$model->id_user', '$model->role', '".Url::to(['/user_project/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'user-project-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-group"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', [
                    "onclick"=>"actionCreate('$model->id_project','".Url::to(['/user_project/create',])."')",
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
    function action(id_project, id_user, role, url, action) {
        $.ajax({
            url: url,
            type: 'Get',
            data: {id_project:id_project,id_user:id_user,role:role},
            success:function(data){
                $('#modal').modal('show').find('#modalContent').html(data);
                if (action == 'update')
                    nombrarEditar($('.modelo').attr('id'));
                else
                    nombrarVer($('.modelo').attr('id'));
            },
            fail: function(){alert("error")}
        });
    }

    function actionDelete(id_project, id_user, role, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id_project='+id_project+'&id_user='+id_user+'&role='+role,
                    type: 'POST',
                    success:function(data){
                        if (data == "Error"){
                            krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                        } else if (data == "Ok") {
                            $.pjax.reload({container: '#user-project-pjax'});
                            $(document).find('#modal').modal('hide');
                            krajeeDialogSuccess.alert('El usuario ha sido eliminado del equipo.');
                        } else if (data == "Boss"){
                            krajeeDialogError.alert("No se ha podido eliminar, el jefe de proyecto no se puede eliminar.");
                        } else if (data == "Used"){
                            let alerta = "<p>No se ha podido eliminar el usuario, tiene asociado:</p><ul>" +
                                "<li>1 - Planes de rextracción de lemas</li>" +
                                "<li>2 - Planes de revisión de lemas extraidos</li>" +
                                "<li>3 - Planes de extracción de documentos complementarios</li>" +
                                "<li>4 - Planes de revisión de documentos complementarios extraidos</li>" +
                                "<li>5 - Planes de confección de documentos</li>" +
                                "<li>6 - Planes de redaccion de lemas</li>" +
                                "<li>7 - Planes de revisión de lemas redactados</li>" +
                                "<li>8 - Planes de asociación de ilustración</li>" +
                                "<li>9 - Planes de revisión de ilustraciones asociadas</li>" +
                                "</ul>";
                            krajeeDialogError.alert(alerta);
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