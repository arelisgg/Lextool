<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\DictionaryType */

$this->title = $model->id_dictionary_type;
$this->params['breadcrumbs'][] = ['label' => 'Dictionary Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dictionary-type-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-book"></i> '. $model->type .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?= Html::button('Editar', ["onclick"=>"editar('$model->id_dictionary_type')", "title"=>"Editar", 'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
                "onclick"=>"eliminar('$model->id_dictionary_type', 'tipo de diccionario', 'dictionary-type-pjax')",
                "title"=>"Eliminar",
                'class' => 'btn btn-danger',
        ]) ?>
    </p>

</div>
