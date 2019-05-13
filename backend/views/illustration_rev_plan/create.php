<?php

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationRevPlan */

$this->title = 'Create Illustration Rev Plan';
$this->params['breadcrumbs'][] = ['label' => 'Illustration Rev Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="illustration-rev-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
