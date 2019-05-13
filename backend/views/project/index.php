<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proyectos lexicográficos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">


    <?php Pjax::begin([
        'id' => 'project-pjax',
        //'timeout' => 10000,
    ]);
    ?>
    <?= GridView::widget([
        'id' => 'project-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'dictionary_type',
                'value' => 'dictionaryType.type',
            ],
            //'description',
            'status',
            [
                'attribute' => 'plant_file',
                'format' => 'raw',
                'value'=>function ($model, $index, $widget) {
                    return $model->plant_file != 'null' ? '<a data-pjax=0 href="' . $model->getPlantUrl() . '">Descargar fichero</a>' : $model->getPlantUrl();
                }
            ],
            'start_date',
            'end_date',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'delete' => function ($url,$model,$key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                "onclick"=>"actionDelete('$model->id_project', '".Url::to(['/project/delete',])."')",
                                "title"=>"Eliminar"]);
                    },
                ],
            ],
        ],

        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'project-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-book"></i> '.$this->title.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', 'create', ['data-pjax'=>0, 'class' => 'btn btn-success', "title"=>"Agregar"]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
            //$fullExportMenu,
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
