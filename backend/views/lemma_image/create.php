<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaImage */

$this->title = 'Create Lemma Image';
$this->params['breadcrumbs'][] = ['label' => 'Lemma Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lemma-image-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
