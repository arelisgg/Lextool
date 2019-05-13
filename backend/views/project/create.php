<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Project */
/* @var $modelTeams backend\models\UserProject[] */
/* @var $modelSources backend\models\Source[] */

$this->title = 'Crear proyecto';
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelTeams' => $modelTeams,
        'modelSources' => $modelSources,
    ]) ?>

</div>
