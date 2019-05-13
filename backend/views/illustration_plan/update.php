<?php

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationPlan */

$this->title = 'Update Illustration Plan: ' . $model->id_illustration_plan;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_illustration_plan, 'url' => ['view', 'id' => $model->id_illustration_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="illustration-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
