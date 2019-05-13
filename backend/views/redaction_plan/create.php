<?php


/* @var $this yii\web\View */
/* @var $model backend\models\RedactionPlan */

$this->title = 'Create Redaction Plan';
$this->params['breadcrumbs'][] = ['label' => 'Redaction Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redaction-plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
