<?php


/* @var $this yii\web\View */
/* @var $model backend\models\LemmaExtPlan */

$this->title = 'Update Lemma Ext Plan: ' . $model->id_lemma_ext_plan;
$this->params['breadcrumbs'][] = ['label' => 'Lemma Ext Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_lemma_ext_plan, 'url' => ['view', 'id' => $model->id_lemma_ext_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lemma-ext-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
