<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuario-Rol';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index">


    <?php
    Modal::begin([
        'header' => '<h2 id=2 class="modelo"></h2>',
        'id' => 'modal',
        'options' => [
            'tabindex' => false
        ],
        //'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    Pjax::begin([
        'id' => 'auth-assignment-pjax',
        //'timeout' => 10000,
    ]);



    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute'=>'usuario',
            'value' =>  'user.username',
            'group' => true,
        ],
        'item_name',
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'update' => function ($url,$model,$key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                        '', ["onclick"=>"editarUsuarioRol('$model->item_name','$model->user_id')", "title"=>"Actualizar"]);
                },
                'view' => function ($url,$model,$key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                        '', ["onclick"=>"verUsuarioRol('$model->item_name','$model->user_id')", "title"=>"Ver"]);
                },
            ],],
    ];
    ?>

    <?= GridView::widget([
        'id' => 'auth-assignment-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'auth-assignment-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i>  Usuario - <i class="glyphicon glyphicon-briefcase"></i> Rol</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', ["onclick"=>"agregar()", 'class' => 'btn btn-success', "title"=>"Agregar"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],

            '{toggleData}',
            '{export}',
            //$fullExportMenu,
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
