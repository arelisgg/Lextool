<?php
use kartik\sidenav\SideNav;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = $model->name;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>


<div class="col-lg-2" style="padding-left: 0px">



    <?= SideNav::widget([
        'heading' => '<i class="glyphicon glyphicon-cog"></i> Menu',
        'type' => SideNav::TYPE_DEFAULT,
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Equipo', 'icon' => '',
                'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/user_project/index'])."')",]
                //'visible' => Yii::$app->user->can('Jefe de Proyecto'),
            ],
            ['label' => 'Fuentes', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/source/index',])."')",]],
            ['label' => 'Lemas', 'items' => [
                ['label' => 'Plan de Extracción', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/lemma_ext_plan/index',])."')",]],
                ['label' => 'Extracción de Lemas', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/lemma_ext_task/index',])."')",]],
                //['label' => 'Extraccón de Lemas', 'url' => ['/lemma_ext_task/index']],
                ['label' => 'Plan de Revisión', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/lemma_rev_plan/index',])."')",]],
                ['label' => 'Revisión de Lemas', 'url' => ['/lemma_rev_task/index']],
                ['label' => 'Conformar Lemario', 'url' => ['/lemmario/index']],

            ],
                //'visible' => Yii::$app->user->can('Jefe de Proyecto'),
            ],
            ['label' => 'Macroestructura', 'items' => [
                ['label' => 'Plan de Extracción', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/doc_ext_plan/index',])."')",]],
                ['label' => 'Extracción de Documentos', 'url' => ['/doc_ext_task/index']],
                ['label' => 'Plan de Revisión', 'options' => ["onclick"=>"actionIndex('$model->id_project','".Url::to(['/doc_rev_plan/index',])."')",]],
                ['label' => 'Revisión de Documentos', 'url' => ['/doc_rev_task/index']],
                ['label' => 'Cargar Documentos', 'url' => ['/lemmario/index']],
                ['label' => 'Aprobación de Documentos', 'url' => ['/lemmario/index']],

            ],],
        ]
    ]);
    ?>
</div>
<div class="col-lg-10">


    <div id="content">
        <?= DetailView::widget([
            'model' => $model,
            'panel' => [
                'type' => DetailView::TYPE_PRIMARY,
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '.$this->title.'</h3>',
            ],
            'buttons1' => '',
            'attributes' => [
                'name',
                [
                    'attribute' => 'dictionary_type',
                    'value' => $model->getTypeName(),
                ],

                'status',
                [
                    'attribute' => 'plant_file',
                    'format' => 'raw',
                    'value' => $model->plant_file != 'null' ? '<a href="' . $model->getPlantUrl() . '">Descargar fichero</a>' : $model->getPlantUrl(),
                    'type' => DetailView::INPUT_FILE,

                ],
                'start_date',
                'end_date',
                'description',
            ],
        ]) ?>
    </div>
</div>