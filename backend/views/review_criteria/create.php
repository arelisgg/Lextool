<?php

/* @var $this yii\web\View */
/* @var $model backend\models\ReviewCriteria */

$this->title = 'Create Review Criteria';
$this->params['breadcrumbs'][] = ['label' => 'Review Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-criteria-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
