<?php
/**
 * Created by PhpStorm.
 * User: Степан
 * Date: 12.02.2018
 * Time: 16:23
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class GaleryAsset extends AssetBundle {

    public $css = [
        'css/galery/style.css',
    ];

    public $js = [
        'js/jquery.isotope.js',
        'js/isotop.activation.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}