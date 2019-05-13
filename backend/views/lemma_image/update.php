<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaImage */

$this->title = 'Update Lemma Image: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lemma Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_lemma_image]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lemma-image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
