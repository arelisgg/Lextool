<?php


/* @var $this yii\web\View */
/* @var $model backend\models\AuthAssignment */

$this->title = 'Create Auth Assignment';
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
