<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TemplateTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipos de Plantillas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-type-index">

    <?php
    Modal::begin([
        'header' => '<h3 id=23 class="modelo"></h3>',
        'id' => 'modal',
        //'size' => 'modal-lg',
        'options' => [
            'tabindex' => false
        ],
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Pjax::begin([
        'id' => 'template-type-pjax',
        //'timeout' => 10000,
    ]);
    ?>

    <?= GridView::widget([
        'id' => 'dictionary-type-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            '', ["onclick"=>"editar('$model->id_template_type')", "title"=>"Actualizar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '', ["onclick"=>"ver('$model->id_template_type')", "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', ["onclick"=>"eliminar('$model->id_template_type', 'tipo de plantilla', 'template-type-pjax')", "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'template-type-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-language"></i> '. $this->title.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', '', ["onclick"=>"agregar()", 'class' => 'btn btn-success', "title"=>"Agregar"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
            //$fullExportMenu,
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
