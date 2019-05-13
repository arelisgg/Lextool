<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */

$this->title = $model->extracted_lemma;
$this->params['breadcrumbs'][] = ['label' => 'Lemas', 'url' => ['index', 'id_project' => $model->id_project]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
Modal::begin([
    'header' => '<h3 class="modelo">Lema: '.$model->extracted_lemma .'</h3>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'options' => [
        'tabindex' => false
    ],
]);
echo "<div id='modalContent'></div>";
Modal::end();

?>


<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->project->name?></div>
<div class="lemma-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title" style="text-transform: capitalize"><i class="fa fa-language"></i> <?= $this->title ?></h2>
            <div class="pull-right">
                <?=
                Html::a('<span class="glyphicon glyphicon-trash"></span>', ['lemma_finish/delete', 'id' => $model->id_lemma], [
                    'data' => [
                        'confirm' => '¿Está seguro de eliminar este lema por completo?',
                        'method' => 'post',
                    ],
                    "title"=>"Eliminar Lema", "class" => "btn btn-danger btn-sm"
                ]);
                ?>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Proyecto:</strong> <?=$model->project->name?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Plan de extracción:</strong> <?=$model->lemmaExtPlan->ext_plan_name?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Letra:</strong> <?= $model->letter->letter?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Fuente:</strong> <?= $model->source->name ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Usuario extrator:</strong> <?=$model->user->full_name?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Lema extraído:</strong> <?= $model->extracted_lemma ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Homónimo:</strong> <?= $model->homonym ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Lema original:</strong> <?= $model->original_lemma ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Texto original:</strong>
                                <?= $model->original_text != "" && $model->original_text != null ?
                                    $model->original_text :
                                    Html::button('<span class="glyphicon glyphicon-eye-open"></span> Imágenes extraidas', [
                                        'onclick' => 'lemma_image("'.$model->id_lemma.'")',
                                        "title"=>"Ver", 'class' => 'btn btn-link'
                                    ]);
                                ?>
                            </p>
                        </li>

                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Elemento lexicográfico:</strong> <?= $model->substructure ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Extracción aprobada:</strong> <?= $model->agree ? "Sí" : "No" ?></p>
                        </li>
                        <li class="list-group-item">
                            <p class="list-group-item-text"><strong>Lemario:</strong> <?= $model->lemario ? "Sí" : "No" ?></p>
                        </li>

                        <?php
                        if ($model->remark != "") {
                            echo  '<li class="list-group-item">
                                       <p class="list-group-item-text"><strong>Observaciones:</strong> 
                                           '.$model->remark.'
                                       </p>
                                   </li>';
                        }

                        if ($model->lexArticle != null){
                            echo '<li class="list-group-item">
                                    <p class="list-group-item-text"><strong>Redacción finalizada:</strong> '.$model->lexArticle->getFinish() .'</p>
                                </li>

                                <li class="list-group-item">
                                    <p class="list-group-item-text"><strong>Redacción:</strong> '. $model->lexArticle->article.'</p>
                                </li>

                                <li class="list-group-item">
                                    <p class="list-group-item-text"><strong>Redacción aprobada:</strong> '. $model->lexArticle->getReview().' '.
                                Html::button('<span class="glyphicon glyphicon-eye-open"></span> Revisión léxica', [
                                    'onclick' => 'revision_lexica("'.$model->lexArticle->id_lex_article.'")',
                                    "title"=>"Ver", 'class' => 'btn btn-link'
                                ])
                                .
                                Html::button('<span class="glyphicon glyphicon-eye-open"></span> Revisión sin derecho de edición', [
                                    'onclick' => 'revision_no_edition("'.$model->lexArticle->id_lex_article.'")',
                                    "title"=>"Ver", 'class' => 'btn btn-link'
                                ])
                                .'</p>
                                </li>';
                        }

                        ?>

                        <li class="list-group-item" >
                            <p class="list-group-item-text"><strong>Aprobación final:</strong>
                                <lite id="finished"><?= $model->finished ? "Sí" : "No"?></lite>
                                <?= $model->finished
                                    ? Html::button('<span class="glyphicon glyphicon-remove"></span> Desaprobar', [
                                        'onclick' => 'finished("'.$model->id_lemma.'")',
                                        "title"=>"Ver", 'class' => 'btn btn-link', 'id' => 'finished_btn'
                                    ])
                                    : Html::button('<span class="glyphicon glyphicon-ok"></span> Aprobar', [
                                        'onclick' => 'finished("'.$model->id_lemma.'")',
                                        "title"=>"Ver", 'class' => 'btn btn-link', 'id' => 'finished_btn'
                                    ]) ?>
                            </p>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
