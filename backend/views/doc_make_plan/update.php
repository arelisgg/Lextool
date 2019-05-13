<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocMakePlan */

$this->title = 'Update Doc Make Plan: ' . $model->id_doc_make_plan;
$this->params['breadcrumbs'][] = ['label' => 'Doc Make Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_doc_make_plan, 'url' => ['view', 'id' => $model->id_doc_make_plan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doc-make-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
