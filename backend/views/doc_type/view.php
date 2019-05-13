<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DocType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Doc Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doc-type-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> '. $model->name .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right;">
        <?= Html::button('Editar', ["onclick"=>"editar('$model->id_doc_type')", "title"=>"Editar", 'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"eliminar('$model->id_doc_type', 'tipo de documento', 'doc-type-pjax')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>
    </p>

</div>
