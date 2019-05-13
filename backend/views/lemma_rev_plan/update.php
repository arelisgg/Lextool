<?php

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaRevPlan */

$this->title = 'Update Lemma Rev Plan: ' . $model->id_rev_plan;
$this->params['breadcrumbs'][] = ['label' => 'Lemma Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rev_plan, 'url' => ['view', 'id' => $model->id_rev_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lemma-rev-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
