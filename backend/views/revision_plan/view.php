<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\RevisionPlan */

$this->title = $model->id_revision_plan;
$this->params['breadcrumbs'][] = ['label' => 'Revision Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="revision-plan-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Usuario',
                'value'=> $model->user->username,
            ],
            'start_date',
            'end_date',
            [
                'label'=>'Letras',
                'value' => $model->getLettersName(),
            ],
            'type',
            'edition:boolean',
            [
                'label'=>'Componentes lexicográficos',
                'value' => $model->getSubmodelName(),
            ],
            [
                'label'=>'Criterios de revisión',
                'value' => $model->getCriteriasName(),
            ],
            'finished:boolean',
            [
                'label'=>'Atrasado',
                'value' => $model->getLate(),
            ],
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-book"></i> '. $model->project->name .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?= Html::button('Editar', [
            "onclick"=>"actionUpdate('$model->id_revision_plan', '".Url::to(['/revision_plan/update',])."')",
            "title"=>"Editar",
            'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"actionDelete('$model->id_revision_plan', '".Url::to(['/revision_plan/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>

    </p>

</div>
