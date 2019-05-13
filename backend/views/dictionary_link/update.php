<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryLink */

$this->title = 'Update Dictionary Link: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dictionary Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_link]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dictionary-link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
