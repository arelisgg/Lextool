<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Letter */

$this->title = 'Update Letter: ' . $model->id_letter;
$this->params['breadcrumbs'][] = ['label' => 'Letters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_letter, 'url' => ['view', 'id' => $model->id_letter]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="letter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
