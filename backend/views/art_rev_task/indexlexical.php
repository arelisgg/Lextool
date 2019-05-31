<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel backend\models\LemmaSearch */
/* @var $project backend\models\Project */
/* @var $revision_plan backend\models\RevisionPlan */
/* @var $letters backend\models\Letter[] */

$this->title = 'Revisión léxica de lemas';
$this->params['breadcrumbs'][] = ['label' => "Planes de revisión" , 'url' => ['art_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>

<div class="revition-index">
    <?php Pjax::begin([
        'id' => 'revition-pjax',
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
                //'header'=>'Lema',
                'value'=>'extracted_lemma',
            ],
            [
                'attribute'=>'lexArtReviewed',
                'format' => "boolean",
                'width'=>'160px',
                'value'=>'lexArticle.reviewed',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>[1=>'Sí',0=>'No'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>''],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{review}',
                'buttons' => [
                    'review' => function ($url,$model,$key) use ($revision_plan){
                        return Html::a('<span class="fa fa-eraser"></span>',
                            ['art_rev_task/revitionlexical', 'id_lemma' => $model->id_lemma, 'id_revision_plan' => $revision_plan->id_revision_plan], ["title"=>"Revisar", 'data-pjax' => 0]);
                    },
                ],
            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'revition-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-eraser"></i> '. $this->title.' del '. $project->name.'</h3>',
        ],
        'toolbar'=>[
            Html::a('<span class="fa fa-check"></span> Finalizar tarea',
                ['art_rev_task/finish', 'id_revision_plan'=>$revision_plan->id_revision_plan],
                ['data-pjax' => 0, 'class' => 'btn btn-primary pull-left', 'title'=>'Finalizar Tarea']),
            '{export}',
            '{toggleData}',
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'indexlexical?id_revision_plan='.$revision_plan->id_revision_plan, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
                'options' => ['class' => 'btn-group pull-right']
            ],

        ],
        'toggleDataContainer' => ['class' => 'btn-group pull-right'],
        'exportContainer' => ['class' => 'btn-group pull-right'],
        'panelBeforeTemplate' => '<div class="float-left"><div class="btn-toolbar kv-grid-toolbar" role="toolbar">{toolbar}</div></div>'
    ]); ?>
    <?php Pjax::end(); ?>
</div>

