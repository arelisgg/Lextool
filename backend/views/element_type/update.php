<?php

/* @var $this yii\web\View */
/* @var $model backend\models\ElementType */

$this->title = 'Update Element Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Element Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_element_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="element-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
