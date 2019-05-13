<?php

/* @var $this yii\web\View */
/* @var $model backend\models\ReviewCriteria */

$this->title = 'Update Review Criteria: ' . $model->id_review_criteria;
$this->params['breadcrumbs'][] = ['label' => 'Review Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_review_criteria, 'url' => ['view', 'id' => $model->id_review_criteria]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="review-criteria-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
