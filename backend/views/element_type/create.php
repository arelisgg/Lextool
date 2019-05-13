<?php

/* @var $this yii\web\View */
/* @var $model backend\models\ElementType */

$this->title = 'Create Element Type';
$this->params['breadcrumbs'][] = ['label' => 'Element Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
