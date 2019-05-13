<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryType */

$this->title = 'Create Dictionary Type';
$this->params['breadcrumbs'][] = ['label' => 'Dictionary Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionary-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
