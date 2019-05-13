<?php

use yii\bootstrap\Modal;
use backend\models\Letter;

/* @var $lemmas backend\models\Lemma[] */
/* @var $model backend\models\Project */

$this->title = 'Agregar lema';
$this->params['breadcrumbs'][] = ['label' => 'Lemario', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<div class="lemma-index">

    <?php
        Modal::begin([
            'header' => '<h3 class="modelo">Imágenes contextuales</h3>',
            'id' => 'modal',
            'size' => 'modal-lg',
            'options' => [
                'tabindex' => false
            ],
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php
            foreach (Letter::find()->all() as $letter){
                echo '<li class="list-clicked">
                            <a class="tab-clicked" href="#'.$letter->id_letter.'" data-toggle="tab" style="padding: 10px 11px;">
                                '.$letter->letter.'
                            </a>
                        </li>';
            }
            ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="row">

                    <div class="col-lg-3">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h2 class="box-title"><i class="fa fa-language"></i> Lemas</h2>
                            </div>
                            <div class="box-body" style="overflow-y: auto; padding: 0px;">
                                <div id="lemas">
                                    <select multiple class="form-control" style="height: 465px; border-color: #fff;">
                                        <?php
                                        foreach ($lemmas as $lemma){
                                            echo '<option id="'.$lemma->id_lemma.'" onclick="options('.$lemma->id_lemma.')">'.$lemma->extracted_lemma.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9" style="padding-left: 0px">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h2 class="box-title"><i class="fa fa-gears"></i> Opciones</h2>

                            </div>
                            <div class="box-body" style="padding: 0px;">
                                <div id="options">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    function image(id){
        $.ajax({
            url: 'image',
            type: 'Get',
            data: {id:id},
            success:function(data){
                $('#modal').modal('show').find('#modalContent').html(data);
            },
            fail: function(){alert("error")}
        });
    }
</script>