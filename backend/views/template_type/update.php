<?php

/* @var $this yii\web\View */
/* @var $model backend\models\TemplateType */

$this->title = 'Update Template Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Template Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_template_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="template-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
