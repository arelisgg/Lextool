<?php

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaExtPlan */

$this->title = 'Create Lemma Ext Plan';
$this->params['breadcrumbs'][] = ['label' => 'Lemma Ext Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lemma-ext-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
