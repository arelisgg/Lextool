<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticle */

$this->title = 'Update Lex Article: ' . $model->id_lex_article;
$this->params['breadcrumbs'][] = ['label' => 'Lex Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_lex_article, 'url' => ['view', 'id' => $model->id_lex_article]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lex-article-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
