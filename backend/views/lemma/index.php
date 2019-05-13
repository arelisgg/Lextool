<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LemmaSearch */
/* @var $model backend\models\Project */
/* @var $letters backend\models\Letter[] */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conformar lemario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="lemario-index">

    <?php Pjax::begin([
        'id' => 'lemario-pjax',
        //'timeout' => 10000,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'id_letter',
                //'format' => "boolean",
                'width'=>'95px',
                'value'=>'letter.letter',
                'group' => true,
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map($letters,'id_letter', 'letter'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            'extracted_lemma',
            [
                'attribute'=>'usuario',
                'value' =>  'user.full_name',
                //'group' => true,
            ],
            [
                'attribute'=>'source_name',
                'value' =>  'source.name',
            ],
            [
                'attribute'=>'homonym',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'homonym',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            //'original_lemma',
            //'structure',
            //'substructure',
            //'original_text',
            //'remark',
            //'agree:boolean',
            //'finished:boolean',
            //'lemario:boolean',
            //'homonym:boolean',
            //'id_lemma_ext_plan',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['lemma/delete', 'id' => $model->id_lemma], [
                            'data' => [
                                'confirm' => '¿Está seguro de eliminar este lema del lemario?',
                                'method' => 'post',
                            ],
                            "title"=>"Eliminar del Lemario"
                        ]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'lemario-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-language"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', 'lemario?id_project='.$model->id_project, [
                    'data-pjax' => 0,
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
