<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Element */

$this->title = 'Editar elemento lexicográfico: ' . $model->elementType->name;
$this->params['breadcrumbs'][] = ['label' => 'Elementos lexicográficos', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = ['label' => $model->elementType->name, 'url' => ['view', 'id' => $model->id_element]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="element-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelSubElements' => $modelSubElements,
    ]) ?>

</div>
