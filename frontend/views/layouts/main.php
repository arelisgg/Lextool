<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
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
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <div class="container" style="padding-top: 20px; padding-bottom: 10px">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= "LexTool" ?> <?= date('Y') ?></p>

        <p class="pull-right"><a id="copyright_p_a" style="cursor:pointer;" onclick="about_developers()">Desarrolladores</a></p>
    </div>
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

<div style="background-image:url('<?=$baseUrl . "/assets/img/works/email.png"?>') ">
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
