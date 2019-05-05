<?php

use app\models\User;
use yii\helpers\Url;

$this->title = 'Вариации тягачей - J.B. Hunt';

$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/fotorama.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/fotorama.js',
    ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]); ?>

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <?php $game = Yii::$app->request->get('game'); ?>
        <div class="col m6 s12">
            <a style="font-weight: bold" class="btn-flat waves-effect right indigo-text">
                Отделение ETS2
            </a>
        </div>
        <div class="col m6 s12">
            <a href="<?= Url::to(['variations/index', 'game' => 'ats']) ?>" class="btn-flat waves-effect left">
                Отделение ATS
            </a>
        </div>
    </div>
    <div id="1">
        <h5>Вариация №1</h5>
        <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var1.jpg">
    </div>
    <div id="2">
        <h5>Вариация №2</h5>
        <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var2.jpg">
    </div>
    <div id="trailer" style="margin-bottom: 15px;">
        <h5>Прицеп J.B. Hunt</h5>
        <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/trailer.jpg">
    </div>
</div>
<?php if(User::isAdmin()): ?>
    <div class="fixed-action-btn">
        <a href="<?=Url::to(['variations/edit'])?>" class="btn-floating btn-large red tooltipped waves-effect waves-light" data-position="left" data-tooltip="Редактировать">
            <i class="large material-icons notranslate">mode_edit</i>
        </a>
    </div>
<?php endif; ?>