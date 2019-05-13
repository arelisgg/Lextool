<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */
/* @var $searchModel backend\models\ElementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Elementos lexicográficos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="element-index">

    <?php

    Pjax::begin([
        'id' => 'element-pjax',
        //'timeout' => 10000,
    ]);
    ?>

    <?= GridView::widget([
        'id' => 'source-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'element_name',
                'value' => 'elementType.name',
            ],
            /*'property',
            [
                'attribute'=>'visibility',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'visibility',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],*/
            'font',
            'font_size',
            [
                'attribute' => 'color',
                'format' => 'raw',
                'value'=>function ($model, $index, $widget) {
                    return '<span  style="
                                        background-color: '.$model->getColorFont().';
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>';
                }
            ],
            [
                'attribute' => 'background',
                'format' => 'raw',
                'value'=> function ($model, $index, $widget) {
                    return '<span  style="
                                        background-color: '.$model->getColorBack().';
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>';
                }
            ],
            [
                'attribute'=>'font_weight',
                'format' => "raw",
                'width'=>'95px',
                'value'=> function ($model, $index, $widget) {
                    return '<div class="btn" type="button"
                                     style="'.$model->getFontWeightStyle().'">
                                    <span class="fa fa-bold"></span>
                                </div>';
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['normal'=>'Normal','bold'=>'Negrita'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'font_style',
                'format' => "raw",
                'width'=>'95px',
                'value'=> function ($model, $index, $widget) {
                    return '<div class="btn" type="button"
                                     style="'.$model->getFontStyle().'">
                                    <span class="fa fa-italic"></span>
                                </div>';
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['normal'=>'Normal','italic'=>'Italic'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'text_decoration',
                'format' => "raw",
                'width'=>'95px',
                'value'=> function ($model, $index, $widget) {
                    return '<div class="btn" type="button"
                                     style="'.$model->getTextDecorationStyle().'">
                                    <span class="fa fa-underline"></span>
                                </div>';
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['none'=>'Normal','underline'=>'Subrayado'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'attribute'=>'text_transform',
                'format' => "raw",
                'width'=>'95px',
                'value'=> function ($model, $index, $widget) {
                    return '<div class="btn" type="button"
                                     style="'.$model->getTextTransformStyle().'">
                                    <span class="fa fa-text-height"></span>
                                </div>';
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>['none'=>'Normal','uppercase'=>'Mayúscula'],
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
                            ['update', "id"=>$model->id_element], [
                                'data-pjax' => 0,
                                "title"=>"Editar"]);
                    },
                    'view' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            ['view', "id"=>$model->id_element], [
                                'data-pjax' => 0,
                                "title"=>"Ver"]);
                    },
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_element', '".Url::to(['/element/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'element-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-language"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create', "id_project"=>$model->id_project], [
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
