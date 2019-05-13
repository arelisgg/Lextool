<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Source */

$this->title = 'Create Source';
$this->params['breadcrumbs'][] = ['label' => 'Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="source-create" class="source-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
