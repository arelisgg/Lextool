<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',

        //AdminLTE
        "template/bower_components/bootstrap/dist/css/bootstrap.css",
        "template/bower_components/font-awesome/css/font-awesome.css",
        "template/bower_components/Ionicons/css/ionicons.css",
        "template/dist/css/AdminLTE.css",
        "template/dist/css/skins/skin-blue.css",


        //adonay
        'css/components.css',
        'css/plugins.css',
    ];
    public $js = [
        'js/main.js',

        //AdminLTE
        "template/dist/js/adminlte.js",
        "template/dist/js/demo.js",

        //Para mostrar pdf
        'js/pdfjs/build/pdf.js',
        'js/pdfjs/build/pdf.worker.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
