<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ComplementaryDocSearch */
/* @var $model backend\models\Project */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos Complementarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="complementary-doc-index">

    <?php Pjax::begin([
        'id' => 'complementary-doc-pjax',
        //'timeout' => 10000,
    ]); ?>

    <?= GridView::widget([
        'id' => 'complementary-doc-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'doc_name',
                'value' =>  'docType.name',
            ],
            //'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value'=>function ($model, $index, $widget) {
                    return $model->url != 'null' ? '<a data-pjax=0 href="' . $model->getSourceUrl() . '">Descargar fichero</a>' : $model->getSourceUrl();
                }
            ],
            [
                'attribute'=>'accepted',
                'format' => "boolean",
                'width'=>'95px',
                'value'=>'accepted',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            //'accepted:boolean',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{accepted}',
                'buttons' => [
                    'accepted' => function ($url,$model,$key) {
                        return Html::a('<span class="fa fa-legal"></span>',
                            'accepted?id='.$model->id_complementary_doc, ["title"=>"Aprobar"]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'complementary-doc-pjax']],
        'responsive'=> true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '. $this->title.' del '. $model->name.'</h3>',
        ],
        'toolbar'=> [
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
