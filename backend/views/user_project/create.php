<?php

/* @var $this yii\web\View */
/* @var $model backend\models\UserProject */

$this->title = 'Create User Project';
$this->params['breadcrumbs'][] = ['label' => 'User Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="user-project-create" class="user-project-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
