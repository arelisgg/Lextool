<?php

use yii\helpers\Url;

/* @var $plans backend\models\DocMakePlan[] */
/* @var $project backend\models\Project */

$this->title = 'Planes de confección de documentos complementarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="plans">

    <section id="demos">
        <div class="row">
            <div class="large-12 columns">
                <div class="owl-carousel owl-theme">
                    <?php
                    foreach ($plans as $plan) {
                        if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                            echo '<div class="item" style="padding: 25px 20px">
                                      <div class="card" style="width: 100%">
                                          <div class="card-body">
                                              <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="../../web/uploads/project/image/'. $project->image .'" alt="'.$project->image.'">
                                              <h3>' . $project->name . '</h3>
                                              <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                              <p><strong>Documentos:</strong> ' . $plan->getDocsName() . '</p>
                                              <p style="color:#dd4b39;"><strong>Estado:</strong> Atrasado </p>
                                              <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                              <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                              <a class="btn btn-danger" href="' . Url::to(['document_make/index', 'id_doc_make_plan' => $plan->id_doc_make_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                          </div>
                                      </div>
                                 </div>';
                        } else {
                            echo '<div class="item" style="padding: 25px 20px">
                                       <div class="card" style="width: 100%">
                                           <div class="card-body">
                                               <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="../../web/uploads/project/image/'. $project->image .'" alt="'.$project->image.'">
                                               <h3>' . $project->name . '</h3>
                                               <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                                               <p><strong>Documentos:</strong> ' . $plan->getDocsName() . '</p>
                                               <p style="color:#00a65a;"><strong>Estado:</strong> En Tiempo </p>
                                               <p><strong>Fecha Inicio: </strong>' . $plan->start_date . '</p>
                                               <p> <strong>Fecha Fin: </strong> ' . $plan->end_date . '</p>
                                               <a class="btn btn-success" href="' . Url::to(['document_make/index', 'id_doc_make_plan' => $plan->id_doc_make_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                                           </div>
                                       </div>
                                 </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

</div>
