<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryLink */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Enlaces de Diccionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dictionary-link-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'link:url',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-external-link"></i> '. $model->name .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?= Html::button('Editar', ["onclick"=>"editar('$model->id_link')", "title"=>"Editar", 'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"eliminar('$model->id_link', 'enlace del diccionario', 'dictionary-link-pjax')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>
    </p>


</div>
