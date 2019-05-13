<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SubModel */

$this->title = 'Ver componente lexicográfico: '.$model->name;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Componentes', 'url' => ['index','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
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
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>',
                    ['update', 'id' => $model->id_sub_model],
                    ['class' => 'btn btn-primary btn-sm', 'title' => 'Editar']) ?>

                <button id="delete_sub_model" class="btn btn-danger btn-sm" title="Eliminar">
                    <i class="glyphicon glyphicon-trash"></i>
                </button>
            </p>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Proyecto</h4>
                            <p class="list-group-item-text"><?= $project->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Nombre</h4>
                            <p class="list-group-item-text"><?= $model->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Se repite</h4>
                            <p class="list-group-item-text">
                                <?php
                                if ($model->repeat){
                                    echo 'Sí';
                                }else {
                                    echo 'No';
                                }

                                ?>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Requerido</h4>
                            <p class="list-group-item-text">
                                <?php
                                if ($model->required){
                                    echo 'Sí';
                                }else {
                                    echo 'No';
                                }

                                ?>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading ">Estructura del componente</h4>
                            <section id="visualisation" style="margin-top: -220px !important; height: 750px !important; overflow: hidden;"></section>
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
            url: '/lextool/backend/web/sub_model/list',
            type: 'get',
            data: { id: <?= $model->id_sub_model ?> },
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

        });
		
		  $("#delete_sub_model").on('click' , function () {
            krajeeDialogWarning.confirm("¿Está seguro de eliminar este componente?", function (result) {
                if (result) {
                    $.ajax({
                        url: "<?= Url::to(['/sub_model/view-delete',])?>",
                        data: { id: <?= $model->id_sub_model ?> },
                        type: 'get',
                        success:function(data) {
                            if (data == "Error") {
                                krajeeDialogError.alert("No se ha podido eliminar el componente porque ya ha sido asociado al modelo de artículo, ha ocurrido un error.");
                            }
                        }
                    })
                }
            });
        })
    });
</script>

