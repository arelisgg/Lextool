<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocExtPlan */

$this->title = 'Update Doc Ext Plan: ' . $model->id_doc_ext_plan;
$this->params['breadcrumbs'][] = ['label' => 'Doc Ext Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_doc_ext_plan, 'url' => ['view', 'id' => $model->id_doc_ext_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doc-ext-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
