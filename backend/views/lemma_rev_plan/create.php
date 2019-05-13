<?php

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaRevPlan */

$this->title = 'Create Lemma Rev Plan';
$this->params['breadcrumbs'][] = ['label' => 'Lemma Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lemma-rev-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
