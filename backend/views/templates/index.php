<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Plantillas';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>

<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-index">


    <?php Pjax::begin([
        'id' => 'template_pjax'
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'attribute'=>'name',


            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',

            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'template_pjax']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-file-archive-o"></i> '. $this->title.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/templates/datos','id_project' => $project->id_project]), [
                    'class' => 'btn btn-success',
                    "title"=>"Agregar Plantilla"]). ' '.

               Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_project='.$project->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
        ],
        'responsive' => true,
        'hover' => true
    ]); ?>
    <?php Pjax::end(); ?>
</div>


