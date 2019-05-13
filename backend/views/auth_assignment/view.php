<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthAssignment */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="auth-assignment-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Usuario',
                'value'=> $model->user->username,
            ],
            'item_name',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i>  Usuario - <i class="glyphicon glyphicon-briefcase"></i> Rol</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right;">
        <?= Html::button('Editar', ["onclick"=>"editarUsuarioRol('$model->item_name','$model->user_id')", 'class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['eliminar', 'item_name' => $model->item_name, 'username' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
