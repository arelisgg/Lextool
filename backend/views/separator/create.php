<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Separator */

$this->title = 'Create Separator';
$this->params['breadcrumbs'][] = ['label' => 'Separators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="separator-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
