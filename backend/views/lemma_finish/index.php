<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Letter;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LemmaSearch */
/* @var $model backend\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $letters backend\models\Letter[] */


$this->title = 'Actualizar lemario';
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
            [
                'attribute'=>'lemma',
                'value'=>'extracted_lemma',
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

            [
                'attribute'=>'extReviewed',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'agree',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            [
                'attribute'=>'lemario',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'lemario',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            [
                'attribute'=>'lexArtFinished',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'lexArticle.finished',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

            [
                'attribute'=>'lexArtReviewed',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'lexArticle.reviewed',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],

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
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="fa fa-legal"></span>', ['lemma_finish/view', 'id' => $model->id_lemma], [
                            "title"=>"Aprobar"
                        ]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['lemma_finish/delete', 'id' => $model->id_lemma], [
                            'data' => [
                                'confirm' => '¿Está seguro de eliminar este lema por completo?',
                                'method' => 'post',
                            ],
                            "title"=>"Eliminar Lema"
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_project='.$model->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
