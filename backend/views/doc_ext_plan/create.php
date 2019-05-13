<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocExtPlan */

$this->title = 'Create Doc Ext Plan';
$this->params['breadcrumbs'][] = ['label' => 'Doc Ext Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-ext-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
