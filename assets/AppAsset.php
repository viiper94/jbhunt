<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use app\models\User;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/materialize/css/materialize.min.css',
        'assets/css/style.css',
    ];
    public $js = [
        '/assets/js/jquery.ui.widget.js',
        '/assets/js/jquery.iframe-transport.js',
        '/assets/js/jquery.fileupload.js',
        'assets/materialize/js/materialize.js',
        'assets/js/script.js?0405',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init(){
        parent::init();
        if(User::isAdmin()) $this->js[] = 'assets/js/admin.js?0405';
    }
}