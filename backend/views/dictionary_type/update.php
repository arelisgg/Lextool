<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryType */

$this->title = 'Update Dictionary Type: ' . $model->id_dictionary_type;
$this->params['breadcrumbs'][] = ['label' => 'Dictionary Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dictionary_type, 'url' => ['view', 'id' => $model->id_dictionary_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dictionary-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
