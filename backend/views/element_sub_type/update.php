<?php


/* @var $this yii\web\View */
/* @var $model backend\models\ElementSubType */

$this->title = 'Update Element Sub Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Element Sub Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_element_sub_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="element-sub-type-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
