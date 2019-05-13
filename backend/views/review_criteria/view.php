<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ReviewCriteria */

$this->title = $model->id_review_criteria;
$this->params['breadcrumbs'][] = ['label' => 'Review Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="review-criteria-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'criteria',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-eraser"></i> '. $model->criteria .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?= Html::button('Editar', ["onclick"=>"editar('$model->id_review_criteria')", "title"=>"Editar", 'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"eliminar('$model->id_review_criteria', 'criterio de revisión', 'review-criteria-pjax')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>
    </p>

</div>
