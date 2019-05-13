<?php

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationPlan */

$this->title = 'Create Illustration Plan';
$this->params['breadcrumbs'][] = ['label' => 'Illustration Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="illustration-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
