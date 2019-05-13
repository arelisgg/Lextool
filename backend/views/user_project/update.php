<?php

/* @var $this yii\web\View */
/* @var $model backend\models\UserProject */

$this->title = 'Update User Project: ' . $model->id_project;
$this->params['breadcrumbs'][] = ['label' => 'User Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_project, 'url' => ['view', 'id_project' => $model->id_project, 'id_user' => $model->id_user, 'role' => $model->role]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div id="user-project-update" class="user-project-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
