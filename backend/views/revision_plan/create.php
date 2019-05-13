<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RevisionPlan */

$this->title = 'Create Revision Plan';
$this->params['breadcrumbs'][] = ['label' => 'Revision Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="revision-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
