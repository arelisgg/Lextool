<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationPlan */

$this->title = $model->id_illustration_plan;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="illustration-plan-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Usuario',
                'value'=> $model->user->username,
            ],
            'type',
            $model->type == "Lema" ? [
                'label'=>'Letras',
                'value' => $model->getLettersName(),
            ] : [
                'label'=>'Componentes',
                'value' => $model->getDocumentsName(),
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
            "onclick"=>"actionUpdate('$model->id_illustration_plan', '".Url::to(['/illustration_plan/update',])."')",
            "title"=>"Editar",
            'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"actionDelete('$model->id_illustration_plan', '".Url::to(['/illustration_plan/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>

    </p>
</div>
