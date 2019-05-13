<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryLink */

$this->title = 'Create Dictionary Link';
$this->params['breadcrumbs'][] = ['label' => 'Dictionary Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionary-link-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
