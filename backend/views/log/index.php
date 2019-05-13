<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trazas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">


    <?php Pjax::begin([
        'id' => 'log-pjax',
    ]); ?>

    <?= GridView::widget([
        'id' => 'log-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute'=>'usuario',
                'value' =>  'user.username',
            ],
            [
                'attribute'=>'rolesName',
                'value' =>  'user.rolesName',
            ],
            [
                'attribute'=>'rolesNameProject',
                'value' =>  'user.rolesNameProject',
            ],
            'ip',
            'action',
            'record',
            'table',
            'date',
            'time',
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'log-pjax']],
        'responsive'=>true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-table"></i> Trazas</h3>',
        ],
        'rowOptions' => function($model, $index, $widget, $grid){
            if($model->action == 'Insertó'){
                return ['class'=>'success'];
            }else if($model->action == 'Editó'){
                return ['class'=>'warning'];
            }else {
                return ['class'=>'danger'];
            }
        },
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
            //$fullExportMenu,
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
