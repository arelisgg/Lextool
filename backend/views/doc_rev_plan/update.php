<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DocRevPlan */

$this->title = 'Update Doc Rev Plan: ' . $model->id_rev_plan;
$this->params['breadcrumbs'][] = ['label' => 'Doc Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_rev_plan, 'url' => ['view', 'id' => $model->id_rev_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doc-rev-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
