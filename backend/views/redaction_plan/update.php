<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RedactionPlan */

$this->title = 'Update Redaction Plan: ' . $model->id_redaction_plan;
$this->params['breadcrumbs'][] = ['label' => 'Redaction Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_redaction_plan, 'url' => ['view', 'id' => $model->id_redaction_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="redaction-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
