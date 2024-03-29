<?php
use yii\helpers\Url;

/* @var $illustration_rev_plans1 backend\models\IllustrationRevPlan[] */
/* @var $illustration_rev_plans2 backend\models\IllustrationRevPlan[] */
/* @var $project backend\models\Project */

$this->title = 'Planes de revisión de ilustración';
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
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
                        foreach ($illustration_rev_plans1 as $plan) {
                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Tipo:</strong> ' . $plan->illustrationPlan->type . '</p>
                                            <p><strong>Letras:</strong> ' . $plan->illustrationPlan->getLettersName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                            <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-danger" href="' . Url::to(['illustration_lemma_rev/index', 'id_illustration_rev_plan' => $plan->id_illustration_rev_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
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
                                            <p><strong>Tipo:</strong> ' . $plan->illustrationPlan->type . '</p>
                                            <p><strong>Letras:</strong> ' . $plan->illustrationPlan->getLettersName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                            <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-success" href="' . Url::to(['illustration_lemma_rev/index', 'id_illustration_rev_plan' => $plan->id_illustration_rev_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                ';
                            }
                            
                        }
                        foreach ($illustration_rev_plans2 as $plan) {
                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                                <div class="item" style="padding: 25px 20px">
                                    <div class="card" style="width: 100%">
                                        <div class="card-body">
                                            <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="'.$project->getImageUrl().'" alt="'.$project->name.'">
                                            <h3>' . $plan->project->name . '</h3>
                                            <p><strong>Tipo:</strong> ' . $plan->illustrationPlan->type . '</p>
                                            <p><strong>Documentos:</strong> ' . $plan->illustrationPlan->getDocumentsName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                            <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-danger" href="' . Url::to(['illustration_document_rev/index','id_illustration_rev_plan' => $plan->id_illustration_rev_plan,]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
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
                                            <p><strong>Tipo:</strong> ' . $plan->illustrationPlan->type . '</p>
                                            <p><strong>Documentos:</strong> ' . $plan->illustrationPlan->getDocumentsName() . '</p>
                                            <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                            <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                            <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                                            <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                                            <a class="btn btn-success" href="' . Url::to(['illustration_document_rev/index', 'id_illustration_rev_plan' => $plan->id_illustration_rev_plan,]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
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