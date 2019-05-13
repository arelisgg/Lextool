<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocMakePlan */

$this->title = 'Create Doc Make Plan';
$this->params['breadcrumbs'][] = ['label' => 'Doc Make Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-make-plan-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
