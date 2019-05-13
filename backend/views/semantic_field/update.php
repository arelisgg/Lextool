<?php

/* @var $this yii\web\View */
/* @var $model backend\models\SemanticField */

$this->title = 'Update Semantic Field: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Semantic Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_semantic_field]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="semantic-field-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
