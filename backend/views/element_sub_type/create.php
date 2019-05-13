<?php


/* @var $this yii\web\View */
/* @var $model backend\models\ElementSubType */

$this->title = 'Create Element Sub Type';
$this->params['breadcrumbs'][] = ['label' => 'Element Sub Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-sub-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
