<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocRevPlan */

$this->title = 'Create Doc Rev Plan';
$this->params['breadcrumbs'][] = ['label' => 'Doc Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-rev-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
