<?php

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationRevPlan */

$this->title = 'Update Illustration Rev Plan: ' . $model->id_illustration_rev_plan;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_illustration_rev_plan, 'url' => ['view', 'id' => $model->id_illustration_rev_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="illustration-rev-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
