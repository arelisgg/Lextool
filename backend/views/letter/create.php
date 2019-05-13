<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Letter */

$this->title = 'Create Letter';
$this->params['breadcrumbs'][] = ['label' => 'Letters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
