<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationRevPlan */

$this->title = $model->id_illustration_rev_plan;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="illustration-rev-plan-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Usuario',
                'value'=> $model->user->username,
            ],
            [
                'label'=>'Plan de ilustraciones',
                'value' => $model->illustrationPlan->getIllustration_plan_name(),
            ],
            'start_date',
            'end_date',
            //'edition:boolean',
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
            "onclick"=>"actionUpdate('$model->id_illustration_rev_plan', '".Url::to(['/illustration_rev_plan/update',])."')",
            "title"=>"Editar",
            'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"actionDelete('$model->id_illustration_rev_plan', '".Url::to(['/illustration_rev_plan/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>

    </p>

</div>
