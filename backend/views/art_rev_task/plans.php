<?php
use yii\helpers\Url;

/* @var $revition_plans_lexical backend\models\RevisionPlan[] */
/* @var $revition_plans_edition backend\models\RevisionPlan[] */
/* @var $revition_plans_noedition backend\models\RevisionPlan[] */
/* @var $project backend\models\Project */

$this->title = 'Planes de revisión';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="plans">
    <div id="app" class="lemma-index">
        <section id="demos">
            <div class="row">
                <div class="large-12 columns">
                    <div class="owl-carousel owl-theme">
                        <?php
                        foreach ($revition_plans_lexical as $plan) {
                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>
                                            <p><strong>Componentes:</strong> ' . $plan->getSubmodelName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-danger" href="' . Url::to(['art_rev_task/indexlexical', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            } else {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>                                            
                                            <p><strong>Componentes:</strong> ' . $plan->getSubmodelName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-success" href="' . Url::to(['art_rev_task/indexlexical', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            }
                        }
                        foreach ($revition_plans_edition as $plan) {
                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>
                                            <p><strong>Edición:</strong> Si </p>                                          
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-danger" href="' . Url::to(['art_rev_task/indexedition', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            } else {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>
                                            <p><strong>Edición:</strong> Si </p>                                     
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-success" href="' . Url::to(['art_rev_task/indexedition', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            }
                        }
                        foreach ($revition_plans_noedition as $plan) {
                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>
                                            <p><strong>Edición:</strong> No </p>
                                            <p><strong>Criterios de revisión:</strong> ' . $plan->getCriteriasName() . '</p>
                                            <p><strong>Componentes:</strong> ' . $plan->getSubmodelName() . '</p>                                          
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-danger" href="' . Url::to(['art_rev_task/indexnoedition', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            } else {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                                            <p><strong>Tipo:</strong> ' . $plan->type . '</p>
                                            <p><strong>Edición:</strong> No </p>
                                            <p><strong>Criterios de revisión:</strong> ' . $plan->getCriteriasName() . '</p>
                                            <p><strong>Componentes:</strong> ' . $plan->getSubmodelName() . '</p>                                         
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                            <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-success" href="' . Url::to(['art_rev_task/indexnoedition', 'id_revision_plan' => $plan->id_revision_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>