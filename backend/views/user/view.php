<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id_user;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            [
                'label'=>'Roles',
                'value' => \backend\models\AuthAssignment::getRolesName($model->id),
            ],
            'full_name',
            'email:email',
            'enabled:boolean',
        ],
        'panel' => [
            'type' => DetailView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> '. $model->full_name .'</h3>',
        ],
        'buttons1' => '',
    ]) ?>

    <p style="text-align: right">
        <?=
        Html::button('Eliminar', [
            "onclick"=>"deleteUser('$model->id_user', '".Url::to(['/user/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ])
        ?>
    </p>

</div>
