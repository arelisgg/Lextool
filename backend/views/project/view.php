<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use backend\models\UserProject;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Proyectos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div id="id_project" class="hidden"><?=$model->id_project?></div>
<div id="name_project" class="hidden"><?=$model->name?></div>
<?= $this->render('detail', [
    'model' => $model,
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider,
    'searchModelSource' => $searchModelSource,
    'dataProviderSource' => $dataProviderSource,
]) ?>
