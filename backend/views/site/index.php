<?php
use backend\models\UserProject;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $projects backend\models\Project[] */

$this->title = 'LexTool';
?>

    <div class="plans">
        <div id="app" class="lemma-index">
            <section id="demos">
                <div class="row">
                    <div class="large-12 columns">
                        <div class="owl-carousel owl-theme">
                            <?php
                            foreach ($projects as $project) {
                                echo ' 
                                    <div class="item" style="padding: 25px 20px">
                                        <div class="card" style="width: 100%">
                                            
                                            <div class="card-body">
                                                <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                                <h3>' . $project->name . '</h3>
                                                <p><strong>Tipo de diccionario:</strong> ' . $project->dictionaryType->type . '</p>
                                                <p><strong>Estado:</strong> ' . $project->status. '</p>
                                                <p><strong>Jefe de proyecto:</strong> ' . UserProject::findOne(['id_project' => $project->id_project, 'role' => 'Jefe de Proyecto'])->user->full_name . '</p>
                                                <p><strong>Planta lexicográfica:</strong> <a href="' . $project->getPlantUrl() . '">Descargar</a></p>
                                                <p><strong>Fecha inicio: </strong>' . $project->start_date . '</p>
                                                <p><strong>Fecha fin: </strong> ' . $project->end_date . '</p>
                                                <a class="btn btn-info" href="' . Url::to(['project/view', 'id' => $project->id_project]) . '">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </div>  
                                    </div>
                                    ';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

