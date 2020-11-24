<?php

/* @var $this yii\web\View */
/* @var $model backend\models\TemplateType */

$this->title = 'Create Template Type';
$this->params['breadcrumbs'][] = ['label' => 'Element Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>