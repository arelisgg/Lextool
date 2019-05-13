<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Source */

$this->title = 'Update Source: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_source]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div id="source-update" class="source-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
