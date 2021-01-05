<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use backend\models\Project;
use kartik\dialog\Dialog;
use yii\bootstrap\Modal;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php
    $baseUrl = Yii::$app->urlManager->baseUrl;
    ?>
    <link rel="shortcut icon" href="<?=$baseUrl?>/favicon.ico" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="skin-blue sidebar-mini fixed">
<div id="url" class="hidden"><?=$baseUrl?></div>
<?php
    $this->beginBody();
    $isGuest = Yii::$app->user->isGuest;
    $isAdministrador = Yii::$app->user->can('Administrador');
    $isJefeProyecto = Yii::$app->user->can('Jefe de Proyecto');



    $id_user = "";
        if(!$isGuest)
            $id_user = Yii::$app->user->identity->getId();
    $items = [];
        if(!$isGuest){
        $projects = Yii::$app->user->identity->projects;
        foreach ($projects as $project){
            $item = '<li><a href="../../web/project/detail?id='.$project->id_project.'"><i class="fa fa-genderless"></i>'.$project->name.'</a></li>';
            array_push($items,$item);
        }
    }
    echo Dialog::widget([
        'libName' => 'krajeeDialogSuccess',
        'options' => ['closable' => true, 'type' => 'type-success'],
    ]);
    echo Dialog::widget([
        'libName' => 'krajeeDialogError',
        'options' => ['closable' => true, 'type' => 'type-danger'],
    ]);
    echo Dialog::widget([
        'libName' => 'krajeeDialogWarning',
        'options' => ['closable' => true, 'type' => 'type-warning'],
    ]);
?>
<div class="wrapper">

    <header class="main-header">
        <a href="<?=Yii::$app->homeUrl?>" class="logo">
            <span class="logo-mini"><b>L</b>T</span>
            <span class="logo-lg"><b>Lex</b>Tool</span>
        </a>

        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown <?=$isJefeProyecto ? "" : "hidden"?>"><a href=<?=Yii::$app->urlManager->baseUrl."/project/index"?> >Proyectos lexicográficos</a></li>

                    <li class="dropdown <?=$isJefeProyecto ? "" : "hidden"?>">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">Nomencladores <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/dictionary_link/index"?> >URL diccionarios</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/dictionary_type/index"?> >Diccionarios</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/doc_type/index"?> >Documentos complementarios</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/semantic_field/index"?> >Campos semánticos</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/element_type/index"?> >Elementos lexicográficos</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/element_sub_type/index"?> >Subelementos lexicográficos</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/review_criteria/index"?> >Criterios de revisión</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/template_type/index"?> >Tipos de Plantillas</a></li>
                        </ul>
                    </li>

                    <li class="dropdown <?=$isAdministrador ? "" : "hidden"?>">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">Administración <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/user/index"?> >Usuarios</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/auth_assignment/index"?> >Usuario-rol</a></li>
                            <li><a href=<?=Yii::$app->urlManager->baseUrl."/log/index"?> >Trazas</a></li>
                        </ul>
                    </li>

                    <li class=<?=!$isGuest ? "" : "hidden"?>>
                        <a href="<?=Url::to(['/site/myprofile?id='.$id_user])?>">Mi perfil</a>
                    </li>

                    <li style="margin-right: 15px">
                        <?php
                        if ($isGuest) {
                            echo "<a href='".Url::to(['/site/login'])."' >Auntenticarse</a>";

                        } else {
                            echo  Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                    'Salir (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm();
                        }
                        ?>

                    </li>
                </ul>
            </div>
        </nav>

    </header>

    <aside class="main-sidebar" style="position: fixed; height: 100%; overflow-y: auto; padding-bottom: 50px;">
        <section class="sidebar">
            <div class="user-panel" style="padding: 0px; white-space: normal">
                <div class="info property hidden" style="padding: 0px; position: static;">
                    <p id="name" style="
                        color: white;
                        font-weight: 600;
                        margin-top: 15px;
                        margin-bottom: 15px;
                        margin-right: 10px;
                        margin-left: 10px"></p>
                </div>
            </div>

            <ul class="sidebar-menu" data-widget="tree">

                <li class="header">Menú de navegación</li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-book"></i><span>Proyectos</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right"><?=count($items)?></span>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="white-space: normal">
                        <?php
                            foreach ($items as $item)
                                echo $item;
                        ?>
                    </ul>
                </li>
                <li class="property hidden"><a id="detail" href=""><i class="fa fa-eye"></i> <span>Detalles</span></a></li>
                <li class="admin hidden"><a id="team" href=""><i class="fa fa-group"></i> <span>Equipo</span></a></li>
                <li class="admin hidden"><a id="source" href=""><i class="fa fa-file-text"></i> <span>Fuentes</span></a></li>
                <li class="treeview admin hidden">
                    <a href="#">
                        <i class="fa fa-sitemap"></i> <span>Artículo lexicográfico</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="white-space: normal">
                        <li><a id="separator" class="property" href=""><i class="fa fa-minus"></i> <span>Serparadores</span></a></li>
                        <li><a id="element" class="property" href=""><i class="fa fa-language"></i>Elementos lexicográficos</a></li>
                        <li><a id="sub_model" class="property" href=""><i class="fa fa-object-ungroup"></i> <span>Componentes lexicográficos</span></a></li>
                        <li><a id="general_model" class="property" href=""><i class="fa fa-object-group"></i>Plantillas</a></li>
                    </ul>
                </li>
                <li class="treeview lemma-menu hidden">
                    <a href="#">
                        <i class="fa fa-language"></i> <span>Lemas</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a id="lemma_ext_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de extracción</span></a></li>
                        <li><a id="lemma_ext_task" class="ext-lemma hidden" href=""><i class="fa fa-pencil-square-o"></i>Extracción de lemas</a></li>
                        <li><a id="lemma_rev_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de revisión</span></a></li>
                        <li><a id="lemma_rev_task" class="rev-lemma hidden" href=""><i class="fa fa-eraser"></i>Revisión de lemas</a></li>
                        <li><a id="lemario" class="admin hidden" href=""><i class="fa fa-language"></i>Conformar lemario</a></li>
                    </ul>
                </li>
                <li class="treeview document-menu hidden">
                    <a href="#">
                        <i class="glyphicon glyphicon-book"></i> <span>Documentos</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu" style="white-space: normal">
                        <li><a id="doc_ext_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de extracción</span></a></li>
                        <li><a id="document_ext_task" class="ext-doc hidden" href=""><i class="fa fa-pencil-square-o"></i>Extracción de documentos</a></li>
                        <li><a id="doc_rev_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de revisión</span></a></li>
                        <li><a id="document_rev_task" class="rev-doc hidden" href=""><i class="fa fa-eraser"></i>Revisión de documentos</a></li>
                        <li><a id="doc_make_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de confección</span></a></li>
                        <li><a id="document_make" class="doc-make hidden" href=""><i class="fa fa-pencil-square"></i>Confección de documentos</a></li>
                        <li><a id="doc_accept" class="admin hidden" href=""><i class="fa fa-legal"></i>Aprobar documentos</a></li>
                    </ul>
                </li>
                <li class="treeview redaction-menu hidden">
                    <a href="#">
                        <i class="fa fa-pencil"></i> <span>Redacción</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a id="art_red_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de redacción</span></a></li>
                        <li><a id="art_red_task" class="redaction hidden" href=""><i class="fa fa-pencil"></i>Redacción de lemas</a></li>
                        <li><a id="art_rev_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de revisión</span></a></li>
                        <li><a id="art_rev_task" class="revition hidden" href=""><i class="fa fa-eraser"></i>Revisión de lemas</a></li>
                    </ul>
                </li>
                <li class="treeview illustration-menu hidden">
                    <a href="#">
                        <i class="fa fa-file-image-o"></i> <span>Ilustración</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a id="illustration_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de ilustración</span></a></li>
                        <li><a id="illustration_task" class="illustration hidden" href=""><i class="fa fa-file-image-o"></i> <span>Asociar ilustración</span></a></li>
                        <li><a id="illustration_rev_plan" class="admin hidden" href=""><i class="fa fa-calendar"></i> <span>Plan de revisión</span></a></li>
                        <li><a id="illustration_rev" class="illustration-rev hidden" href=""><i class="fa fa-file-image-o"></i> <span>Revisar ilustración</span></a></li>
                    </ul>
                </li>
                <li class="admin hidden"><a id="lemma_finish" href=""><i class="fa fa-legal"></i> <span>Actualizar lemario</span></a></li>

            </ul>
        </section>
    </aside>

    <div id="page-container" class="content-wrapper">
        <div class="content">

            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>

            <?= $content ?>
        </div>

    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs" style="margin-right: 30px">
            <a id="copyright_p_a" style="cursor:pointer;" onclick="about_developers()">Desarrolladores</a>
        </div>
        <strong>&copy; <?= "LexTool" ?> <?= date('Y') ?></strong> Todos los derechos reservados.
    </footer>

    <?php
    Modal::begin([
        'header' => '<h2 style="text-align: center">LexTool</h2>',
        'id' => 'about_developers',
        'size' => 'modal-md',
        'options' => [
            'tabindex' => false
        ],
    ]);
    ?>

    <div style="background-image:url('<?=Yii::$app->urlManager->baseUrl . "/assets/img/works/email.png"?>') ">
        <p style="font-size: 16px">Desarrolladores:</p>

        <br>
        <ul>
            <li>Alvaro Escobar Borrego<br>
                <ul><li style="list-style-type: none;"> <a href="mailto:aescobar@ceis.cujae.edu.cu"> <span style="padding-right: 5px" class="glyphicon glyphicon-envelope"></span> aescobar@ceis.cujae.edu.cu</a></li></ul>
            </li>
            <li>Adonai Dominínguez Hernandez<br>
                <ul><li style="list-style-type: none;"> <a href="mailto:adominguez@ceis.cujae.edu.cu"> <span style="padding-right: 5px" class="glyphicon glyphicon-envelope"></span> adominguez@ceis.cujae.edu.cu</a></li></ul>
            </li>

        </ul>

        <br>
        <p style="font-size: 16px">Tutores:</p>

        <br>
        <ul>
            <li>Dr. Carlos Ramón López Paz<br>
                <ul><li style="list-style-type: none;"> <a href="mailto:carlosr@ceis.cujae.edu.cu"> <span style="padding-right: 5px" class="glyphicon glyphicon-envelope"></span> carlosr@ceis.cujae.edu.cu</a></li></ul>
            </li>
            <li>Msr. Claudia Ivette Castro Zamora<br>
                <ul><li style="list-style-type: none;"> <a href="mailto:ccastro@ceis.cujae.edu.cu"> <span style="padding-right: 5px" class="glyphicon glyphicon-envelope"></span> ccastro@ceis.cujae.edu.cu</a></li></ul>
            </li>

        </ul>
    </div>
    <?php
    Modal::end();
    ?>

</div>



<?php $this->endBody() ?>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
</body>
</html>
<?php $this->endPage() ?>
