<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RevisionPlan */

$this->title = 'Update Revision Plan: ' . $model->id_revision_plan;
$this->params['breadcrumbs'][] = ['label' => 'Revision Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_revision_plan, 'url' => ['view', 'id' => $model->id_revision_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="revision-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
