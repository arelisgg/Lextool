<?php


/* @var $this yii\web\View */
/* @var $model backend\models\DocType */

$this->title = 'Create Doc Type';
$this->params['breadcrumbs'][] = ['label' => 'Doc Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
