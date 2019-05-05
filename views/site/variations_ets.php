<?php

use yii\helpers\Url;

$this->title = 'Вариации тягачей - J.B. Hunt';

$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/fotorama.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/fotorama.js',
    ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]); ?>

<div class="container">
    <div id="1">
        <h5>Вариация №1</h5>
        <img class="responsive-img z-depth-1 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var1.jpg">
    </div>
    <div id="2">
        <h5>Вариация №2</h5>
        <img class="responsive-img z-depth-1 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var2.jpg">
    </div>
    <div id="trailer" style="margin-bottom: 15px;">
        <h5>Прицеп J.B. Hunt</h5>
        <img class="responsive-img z-depth-1 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/trailer.jpg">
    </div>
</div>