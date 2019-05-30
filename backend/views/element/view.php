<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Element */

$this->title = $model->elementType->name;
$this->params['breadcrumbs'][] = ['label' => 'Elementos lexicográficos', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->project->name?></div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h2 class="box-title" ><i class="fa fa-book"></i> <?= $model->project->name ?></h2>
        <p class="pull-right" style="margin: 0px">
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>',
                ['update', 'id' => $model->id_element],
                ['class' => 'btn btn-primary btn-sm', 'title' => 'Editar']) ?>
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i>',[
                "onclick"=>"actionDelete('$model->id_element', '".Url::to(['/element/delete_view',])."')",
                "title"=>"Eliminar", 'class' => 'btn btn-danger btn-sm']);
            ?>
        </p>
    </div>
    <div class="box-body">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title" ><i class="fa fa-language"></i> Elemento lexicográfico</h2>

            </div>
            <div class="panel-body" style="padding: 0px">
                <table id="w1" class="table table-bordered table-striped detail-view">
                    <tbody>
                        <tr>
                            <th style="width: 12.5%; text-align: center; vertical-align: middle;">Elemento</th>
                            <th style="width: 12.5%; text-align: center; vertical-align: middle;">Propiedad</th>
                            <th style="width: 10.5%; text-align: center; vertical-align: middle;">Visibilidad</th>
                            <th style="width: 12.5%; text-align: center; vertical-align: middle;">Letra</th>
                            <th style="width: 12.5%; text-align: center; vertical-align: middle;">Tamaño de letra</th>
                            <th style="width: 8.5%; text-align: center; vertical-align: middle;">Color</th>
                            <th style="width: 12.5%; text-align: center; vertical-align: middle;">Color de fondo</th>
                            <th style="width: 18.5%; text-align: center; vertical-align: middle;">Ajustes</th>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;"><?=$model->getTypeName()?></td>
                            <td style="text-align: center; vertical-align: middle;"><?=$model->property?></td>
                            <td style="text-align: center; vertical-align: middle;"><?=$model->visibility==1 ? "Si" : "No" ?></td>
                            <td style="text-align: center; vertical-align: middle;"><?=$model->font?></td>
                            <td style="text-align: center; vertical-align: middle;"><?=$model->font_size?></td>
                            <td style="text-align: center; vertical-align: middle;">
                                <span  style="
                                        background-color: <?='#'.$model->color?>;
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <span  style="
                                        background-color: <?='#'.$model->background?>;
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>
                            </td>
                            <td style="text-align: center;">

                                <div class="form-group" style="margin-bottom: 0px">
                                    <div class="btn" type="button"
                                         style='<?=$model->font_weight=="bold"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;"?>'>
                                        <span class="fa fa-bold"></span>
                                    </div>

                                    <div class="btn" type="button"
                                         style='<?=$model->font_style=="italic"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;"?>'>
                                        <span class="fa fa-italic"></span>
                                    </div>

                                    <div class="btn" type="button"
                                         style='<?=$model->text_decoration=="underline"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;"?>'>
                                        <span class="fa fa-underline"></span>
                                    </div>

                                    <div class="btn" type="button"
                                         style='<?=$model->text_transform=="uppercase"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;"?>'>
                                        <span class="fa fa-text-height"></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-square-o"></i> Subelementos lexicográficos</h2>
            </div>
            <div class="panel-body" style="padding: 0px">
                <table id="w1" class="table table-bordered table-striped detail-view">
                    <tbody>
                    <tr>
                        <th style="width: 18.5%; text-align: center; vertical-align: middle;">Elemento</th>
                        <th style="width: 10.5%; text-align: center; vertical-align: middle;">Visibilidad</th>
                        <th style="width: 12.5%; text-align: center; vertical-align: middle;">Letra</th>
                        <th style="width: 12.5%; text-align: center; vertical-align: middle;">Tamaño de letra</th>
                        <th style="width: 8.5%; text-align: center; vertical-align: middle;">Color</th>
                        <th style="width: 12.5%; text-align: center; vertical-align: middle;">Color de fondo</th>
                        <th style="width: 25%; text-align: center; vertical-align: middle;">Ajustes</th>
                    </tr>

                    <?php
                        $subelementos = $model->subElements;
                        foreach ($subelementos as $subelemento){
                            echo '<tr>
                        <td style="text-align: center; vertical-align: middle;">'.$subelemento->getSubTypeName().'</td>
                        <td style="text-align: center; vertical-align: middle;">'.$subelemento->isVisible().'</td>
                        <td style="text-align: center; vertical-align: middle;">'.$subelemento->font.'</td>
                        <td style="text-align: center; vertical-align: middle;">'.$subelemento->font_size.'</td>
                        <td style="text-align: center; vertical-align: middle;">
                                <span  style="
                                        background-color: '.$subelemento->getColorFont().';
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                                <span  style="
                                        background-color: '.$subelemento->getColorBack().';
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                        -webkit-box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        box-shadow: inset 0 0 2px 2px rgba(0,0,0,0.075);
                                        height: 1.429em;
                                        width: 1.429em;
                                        display: inline-block;
                                        cursor: pointer;
                                        margin-right: 5px;"
                                ></span>
                        </td>
                        <td style="text-align: center;">

                            <div class="form-group" style="margin-bottom: 0px">
                                <div class="btn" type="button"
                                     style="'.$subelemento->getFontWeightStyle().'">
                                    <span class="fa fa-bold"></span>
                                </div>

                                <div class="btn" type="button"
                                     style="'.$subelemento->getFontStyle().'">
                                    <span class="fa fa-italic"></span>
                                </div>

                                <div class="btn" type="button"
                                     style="'.$subelemento->getTextDecorationStyle().'">
                                    <span class="fa fa-underline"></span>
                                </div>

                                <div class="btn" type="button"
                                     style="'.$subelemento->getTextTransformStyle().'">
                                    <span class="fa fa-text-height"></span>
                                </div>
                            </div>
                        </td>
                    </tr>';
                        }
                    ?>






                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
