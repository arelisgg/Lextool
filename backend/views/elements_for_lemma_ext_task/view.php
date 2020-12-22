<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticle */

$this->title = $model->lemma->extracted_lemma;
//$this->params['breadcrumbs'][] = ['label' => $project->name , 'url' => ['project/view','id' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Planes de redacción de artículos lexicográficos' , 'url' => ['art_red_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Redacción de artículos lexicográficos' , 'url' => ['art_red_task/index','id_redaction_plan' => $plan->id_redaction_plan]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="lex-article-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h2 class="box-title"><i class="fa fa-language"></i> <?= Html::encode($this->title) ?></h2>
            <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $model->id_lex_article, 'id_redaction_plan' => $plan->id_redaction_plan], ['class' => ' btn btn-primary btn-sm']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['delete', 'id' => $model->id_lex_article,], [
                    'class' => 'btn btn-danger btn-sm',
                    'data' => [
                        'confirm' => '¿Está seguro de querer eliminar el artículo lexicográfico?',
                        'method' => 'get',
                    ],
                ]) ?>
            </div>
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
                            <h4 class="list-group-item-heading">Letra</h4>
                            <p class="list-group-item-text"><?= $model->lemma->letter->letter ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading">Lema extraído</h4>
                            <p class="list-group-item-text"><?= $model->lemma->extracted_lemma ?></p>
                        </li>
                        <li class="list-group-item">
                            <h4 class="list-group-item-heading margin-bottom-15">Artículo lexicográfico</h4>
                            <div class="list-group-item-text" style="text-align: justify !important;"><?= $model->article?></div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>


