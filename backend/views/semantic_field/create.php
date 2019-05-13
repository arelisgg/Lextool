<?php

/* @var $this yii\web\View */
/* @var $model backend\models\SemanticField */

$this->title = 'Create Semantic Field';
$this->params['breadcrumbs'][] = ['label' => 'Semantic Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semantic-field-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
