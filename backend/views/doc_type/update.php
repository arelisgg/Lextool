<?php

/* @var $this yii\web\View */
/* @var $model backend\models\DocType */

$this->title = 'Update Doc Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Doc Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_doc_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doc-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
