<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Separator */

$this->title = 'Update Separator: ' . $model->id_separator;
$this->params['breadcrumbs'][] = ['label' => 'Separators', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_separator, 'url' => ['view', 'id' => $model->id_separator]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="separator-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
