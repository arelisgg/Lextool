<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TemplateType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Plantillas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="template-type-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-language"></i> '. $model->name .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?= Html::button('Editar', ["onclick"=>"editar('$model->id_template_type')", "title"=>"Editar", 'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"eliminar('$model->id_template_type', 'tipo de plantillas', 'template-type-pjax')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>
    </p>


</div>
