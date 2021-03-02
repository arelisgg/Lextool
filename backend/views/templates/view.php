<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Templates */

$this->title = 'Ver Plantilla: '.$model->name;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Plantillas', 'url' => ['index','id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<style>
    text {
        font-size: 18px !important;
        font-weight: 600 !important;
    }
</style>
<div class="sub-model-view">

    <div class="box box-primary">

        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= $this->title ?></h2>
            <p class="pull-right" style="margin: 0">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                    Url::to(['/templates/updatedatos','id_template' => $model->id_template]),
                    ['class' => 'btn btn-primary btn-sm', 'title' => 'Editar'])

                ?>
                <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                    "onclick"=>"actionDelete('$model->id_template', '".Url::to(['/templates/delete',])."')",
                    "title"=>"Eliminar",
                    'class' => 'btn btn-danger btn-sm',
                ]) ?>

            </p>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?php $project= \backend\models\Project::find()->where(['id_project'=> $model->id_project])->one(); echo $project->name;  ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Nombre</h4>
                            <p class="list-group-item-text"><?= $model->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Tipo de Plantilla</h4>
                            <p class="list-group-item-text"><?php $type= \backend\models\TemplateType::find()->where(['id_template_type'=> $model->id_template_type])->one(); echo $type->name; ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Estructura de la Plantilla</h4>

                            <ul id="general" class="block__list block__list_tags">
                            <?php

                            for($i = 0; $i < count($orderer); $i++) {
                                if ($orderer[$i]->canGetProperty('id_element')){

                                    echo '<li id="'.$orderer[$i]->id_element.'"><span id="name" style="font-weight: bold">'.$orderer[$i]->elementType->name.'</span> <span id="property" style="display: none">('.$orderer[$i]->property.')</span>
                                            <input type="hidden" name="element-'.$orderer[$i]->id_element.'" value="'.$orderer[$i]->id_element.'">
                                              </li>';

                                }else{
                                    if ($orderer[$i]->canGetProperty('name')) {
                                        if (!$orderer[$i]->repeat && $orderer[$i]->required) {
                                            echo '<li class="only-required" id="'.$orderer[$i]->id_sub_model.'"><span id="name" style="font-weight: bold">'.$orderer[$i]->name.' </span> ( <i class="fa fa-check"></i> )
                                                 <input type="hidden" name="submodel-'.$orderer[$i]->id_sub_model.'" value="'.$orderer[$i]->id_sub_model.'">
                                                 </li>';
                                        }elseif (!$orderer[$i]->repeat && !$orderer[$i]->required) {
                                            echo '<li class="full" id="'.$orderer[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$orderer[$i]->name.' </span>
                                                    <input type="hidden" name="submodel-'.$orderer[$i]->id_sub_model.'" value="'.$orderer[$i]->id_sub_model.'">
                                                      </li>';
                                        }
                                        elseif ($orderer[$i]->repeat && $orderer[$i]->required) {
                                            echo '<li class="full" id="'.$orderer[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$orderer[$i]->name.' </span> ( <i class="fa fa-check"></i>, <i class="fa fa-refresh"></i> )
                                                     <input type="hidden" name="submodel-'.$orderer[$i]->id_sub_model.'" value="'.$orderer[$i]->id_sub_model.'">
                                                     </li>';
                                        }elseif ($orderer[$i]->repeat && !$orderer[$i]->required) {
                                            echo '<li class="only-repeat" id="'.$orderer[$i]->id_sub_model.'"> <span id="name" style="font-weight: bold">'.$orderer[$i]->name.'</span> ( <i class="fa fa-refresh"></i> )
                                                     <input type="hidden" name="submodel-'.$orderer[$i]->id_sub_model.'" value="'.$orderer[$i]->id_sub_model.'">
                                                      </li>';
                                        }
                                    }
                                    elseif ($orderer[$i]->canGetProperty('id_separator')) {
                                        if ($orderer[$i]->scope == 'Componente') {
                                            echo '<li id="' . $orderer[$i]->id_separator . '" style="width: 60px; font-weight: bolder; background-color: #00a65a;"><span>' . $orderer[$i]->representation . '</span>
                                                   <input type="hidden" name="separator-' . $orderer[$i]->id_separator . '" value="' . $orderer[$i]->id_separator . '">
                                                   </li>';
                                        }
                                    }
                                }
                            }
                            ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: '/lextool-1.0/backend/web/template/list',
            type: 'get',
            data: { id: <?= $model->id_template ?> },
            success: function (full_data) {
                let root = full_data.pop();

                let id = 1;

                var data = {
                    "id": id,
                    "name": id,
                    "type": "Root",
                    "description": root,
                    "children" : []
                };

                if (full_data.length > 0) {
                    let levels = [];

                    while (full_data.length > 0) {
                        id++;

                        let level = "Level-"+id;
                        let element = full_data.pop();

                        let tree_level = {
                            "id": id,
                            "name": id,
                            "type": level,
                            "description": element,
                            "children": []
                        };

                        levels.push(tree_level);
                    }

                    levels = levels.reverse();

                    let full_tree;

                    for (let i = 0; i < levels.length;i++){
                        let start = levels[i];
                        let next = levels[i+1];
                        let arr = [];

                        if (next == null) {
                            arr.push(start);
                            full_tree = start;
                            break;
                        }

                        arr.push(start);
                        next.children = arr;
                        full_tree = next;
                    }

                    let arr = [];
                    arr.push(full_tree);
                    data.children = arr;
                }

                console.log(data);

                var treePlugin = new d3.mitchTree.boxedTree()
                    .setData(data)
                    .setAllowZoom(false)
                    .setElement(document.getElementById("visualisation"))
                    .setIdAccessor(function(data) {
                        return data.id;
                    })
                    .setChildrenAccessor(function(data) {
                        return data.children;
                    })
                    .setBodyDisplayTextAccessor(function(data) {
                        return data.description;
                    })
                    .setTitleDisplayTextAccessor(function(data) {
                        return data.name;
                    })
                    .initialize();
            }

        })

    });

    function actionDelete(id, url){
        krajeeDialogWarning.confirm("¿Está seguro de eliminar este elemento?", function (result) {
            if (result) {
                $.ajax({
                    url: url+'?id='+id,
                    type: 'POST',
                    success:function(data){
                        if (data == "Error"){
                            krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                        }
                    },
                    fail: function(){
                        krajeeDialogError.alert("No se ha podido eliminar, ha ocurrido un error.");
                    }
                });
            }
        });
    }
</script>
