<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\AuthAssignment;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="user-index">

    <?php

        Modal::begin([
            'header' => '<h2 id=1 class="modelo"></h2>',
            'id' => 'modal',
            //'size' => 'modal-lg',
            'options' => [
                'tabindex' => false
            ],
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();

        Pjax::begin([
            'id' => 'user-pjax',
            //'timeout' => 10000,
        ]);

        $gridColumns = [
            ['class' => 'kartik\grid\SerialColumn'],
            'username',
            'full_name',
            'email:email',
            [
                'attribute'=>'rol',
                'content'=>function($data){
                    return AuthAssignment::getRolesName($data->id);
                }
            ],

            [
                'attribute'=>'enabled',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'enabled',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],


            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{habilitar} {view} {update} {delete}',
                'buttons' => [
                    'habilitar' => function ($url,$model,$key) {
                        return Html::a(
                            $model->enabled ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>',
                            '', ["onclick"=>"habilitar('$model->id_user')",
                                "title"=>"Cambiar Estado",
                                'id'=>"state_".$model->id_user,
                                'data-pjax'=>1,
                                ]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '', ["onclick"=>"ver('$model->id')", "title"=>"Ver"]);
                    },
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '', [
                                "onclick"=>"editar('$model->id')",
                                "title"=>"Editar"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"deleteUser('$model->id_user', '".Url::to(['/user/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ];
    ?>


    <?= GridView::widget([
        'id' => 'user-grid',
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'user-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Usuarios</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', ['onclick'=>'agregar()','class' => 'btn btn-success', "title"=>"Agregar"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            //$fullExportMenu,
            '{toggleData}',
            '{export}',

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    function deleteUser(id, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id='+id,
                    type: 'POST',
                    success:function(data){
                        let result = data.split(',');
                        if (result[0] === "Error"){
                            krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                        } else if (result[0] === "Guest"){
                            krajeeDialogWarning.alert("No se ha podido eliminar, el usuario está autenticado.");
                        } else if (result[0] === "No"){
                            krajeeDialogWarning.alert("No se ha podido eliminar, el usuario tiene asignado algún plan.");
                        } else if (result[0] === "Ok"){
                            $.pjax.reload({container: '#user-pjax'});
                            $(document).find('#modal').modal('hide');
                            if (result[1] == 0)
                                krajeeDialogWarning.alert("El usuario ha sido eliminado pero ha ocurrido un error. No se pudo enviar el correo de confirmación.");
                            else
                                krajeeDialogSuccess.alert("El usuario ha sido eliminado.");
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