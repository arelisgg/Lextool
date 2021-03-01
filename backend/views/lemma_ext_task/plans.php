
<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\LemmaSearch */
/* @var $currentLetter backend\models\Lemma */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planes de extracción de lemas candidatos';
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
                        foreach ($plans as $plan) {

                            if (strtotime(date("Y-m-d")) > strtotime(date($plan->end_date))) {
                                echo ' 
                <div class="item" style="padding: 25px 20px">
                  <div class="card" style="width: 100%">
                    
                    <div class="card-body">
                    <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="../../web/uploads/project/image/'. $project->image .'" alt="'.$project->image.'">
                    <h3>' . $plan->project->name . '</h3>
                    <p><strong>Campos semánticos:</strong> ' . $plan->getSemanticsName() . '</p>
                    <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                    <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                    <p><strong>Plantilla:</strong> ' . $plan->getTemplatesName() . '</p>
                    <p style="color: #dd4b39;"><strong>Estado:</strong> Atrasado </p>
                    <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                    <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                    <a class="btn btn-danger" href="' . Url::to(['lemma_ext_task/index', 'id_ext_plan' => $plan->id_lemma_ext_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>  
                </div>
                ';
                            } else {
                                echo ' 
                <div class="item" style="padding: 25px 20px">
                    <div class="card" style="width: 100%">
                    
                    <div class="card-body">
                    <img style="width: 45%; height: 150px; margin-right: 0px" class="card-img-top" src="../../web/uploads/project/image/'. $project->image .'" alt="'.$project->image.'">
                    <h3>' . $plan->project->name . '</h3>
                    <p><strong>Campos semánticos:</strong> ' . $plan->getSemanticsName() . '</p>
                    <p><strong>Letras:</strong> ' . $plan->getLettersName() . '</p>
                    <p><strong>Usuario:</strong> ' . $plan->user->full_name . '</p>
                    <p style="color: #00a65a;"><strong>Estado:</strong> En Tiempo </p>
                    <p><strong>Fecha inicio: </strong>' . $plan->start_date . '</p>
                    <p> <strong>Fecha fin: </strong> ' . $plan->end_date . '</p>
                    <a class="btn btn-success" href="' . Url::to(['lemma_ext_task/index', 'id_ext_plan' => $plan->id_lemma_ext_plan]) . '">Continuar <i class="fa fa-arrow-circle-right"></i></a>
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
