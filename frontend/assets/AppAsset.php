<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
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

//        'js/owl-carousel/dist/assets/owl.carousel.css',
//        'js/owl-carousel/dist/assets/owl.theme.green.css',
//        'css/carousel.css',


    ];
    public $js = [
        'js/main.js',

//        'js/owl-carousel/dist/owl.carousel.js',
//        'js/init-carousel.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
