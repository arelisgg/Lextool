<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaExtPlan */

$this->title = $model->id_lemma_ext_plan;
$this->params['breadcrumbs'][] = ['label' => 'Lemma Ext Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="lemma-ext-plan-view" class="lemma-ext-plan-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Usuario',
                'value'=> $model->user->username,
            ],

            [
                'label'=>'Campos semánticos',
                'value' => $model->getSemanticsName(),
            ],
            [
                'label'=>'Plantilla',
                'value' => $model->getTemplatesName(),
            ],
            [
                'label'=>'Letras',
                'value' => $model->getLettersName(),
            ],
            [
                'label'=>'Fuentes',
                'value' => $model->getSourcesName(),
            ],
            'start_date',
            'end_date',
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
            "onclick"=>"actionUpdate('$model->id_lemma_ext_plan', '".Url::to(['/lemma_ext_plan/update',])."')",
            "title"=>"Editar",
            'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"actionDelete('$model->id_lemma_ext_plan', '".Url::to(['/lemma_ext_plan/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>

    </p>
</div>
