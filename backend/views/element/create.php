<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Element */

$this->title = 'Crear elemento lexicográfico';
$this->params['breadcrumbs'][] = ['label' => 'Elementos lexicográficos', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelSubElements' => $modelSubElements,
    ]) ?>

</div>
