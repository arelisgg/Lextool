<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Plantillas';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>

<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="sub-model-index">


    <?php Pjax::begin([
        'id' => 'template_pjax'
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'attribute'=>'name',
            [
                'attribute' => 'id_template_type',
                'value' => 'templateType.name',
                'filter'=> \yii\helpers\ArrayHelper::map(\backend\models\TemplateType::find()->asArray()->all(), 'id_template_type','name'),
            ],
            [
                'attribute' => 'ref_file',
                'format' => 'raw',
                'value'=>function ($model, $index, $widget) {
                    return $model->ref_file != 'null' ? '<a data-pjax=0 href="' . $model->getRefFileUrl() . '">Descargar fichero</a>' : $model->getRefFiletUrl();
                }
            ],
            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons'=>[
                        'update'=> function($url,$model,$key){
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                        Url::to(['/templates/updatedatos','id_template' => $model->id_template]),
                                        ["title"=>"Modificar Plantilla"]);
                        },
                ],

            ],
        ],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'template_pjax']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fa fa-file-archive-o"></i> '. $this->title.'</h3>',
        ],
        'toolbar'=>[
            'options' => ['class' => 'pull-left'],
            ['content'=>
                Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(['/templates/datos','id_project' => $project->id_project]), [
                    'class' => 'btn btn-success',
                    "title"=>"Agregar Plantilla"]). ' '.

               Html::a('<i class="glyphicon glyphicon-repeat"></i>', 'index?id_project='.$project->id_project, [ 'class'=>'btn btn-default', 'title'=>'Reiniciar']),
            ],
            '{toggleData}',
            '{export}',
        ],
        'responsive' => true,
        'hover' => true
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    $(document).ready(function () {
        let url = $("#url").get(0).innerText
        let id = $("#id_template").get(0).innerText

        $("#delete_template").click(function () {
            krajeeDialogWarning.confirm("¿Está seguro de eliminar esta plantilla?", function (result) {
                if (result) {
                    $.ajax({
                        url: url + '/templates/verify',
                        type: 'Get',
                        data: { id_template: id },
                        success: function (data) {
                            if (data.can_delete) {
                                $.ajax({
                                    url: url + '/templates/delete',
                                    data: { id_template: id },
                                    type: 'Get',
                                })

                            }else {
                                let message = document.createElement('ul')

                                let count = 1;

                                for (let key in data.error_list) {
                                    let li = document.createElement('li')
                                    li.innerText = count + '-' + data.error_list[key]
                                    message.appendChild(li)
                                    count++
                                }

                                krajeeDialogError.alert(message);
                            }
                        }
                    })
                }
            })
        })
    });
</script>

