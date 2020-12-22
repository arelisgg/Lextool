<?php

use antkaz\vue\VueAsset;
VueAsset::register($this); // register VueAsset
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LexArticle */

$this->title = 'Editar artículo lexicográfico';
$this->params['breadcrumbs'][] = ['label' => 'Planes de redacción de artículos lexicográficos' , 'url' => ['art_red_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => 'Redacción de artículos lexicográficos' , 'url' => ['art_red_task/index','id_redaction_plan' => $plan->id_redaction_plan]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?= $project->id_project?></div>
<div id="name_project" class="hidden"><?= $project->name?></div>
<div id="redaction_plan" class="hidden"><?= $plan->id_redaction_plan?></div>
<div id="lex_article" class="hidden"><?= $lex_article->id_lex_article?></div>
