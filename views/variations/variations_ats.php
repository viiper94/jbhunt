<?php

use app\models\User;
use yii\helpers\Url;

$this->title = 'Вариации тягачей - J.B. Hunt';

?>

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <?php $game = Yii::$app->request->get('game'); ?>
        <div class="col m6 s12">
            <a href="<?= Url::to(['variations/index', 'game' => 'ets2']) ?>" class="btn-flat waves-effect right">
                Отделение ETS2
            </a>
        </div>
        <div class="col m6 s12">
            <a style="font-weight: bold" class="btn-flat waves-effect left indigo-text">
                Отделение ATS
            </a>
        </div>
    </div>
    <div id="1">
        <h5>Вариация №1</h5>
        <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var_us.jpg">
    </div>
    <div id="trailer" style="margin-bottom: 15px;">
        <h5>Прицеп J.B. Hunt</h5>
        <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/trailer_us.jpg">
    </div>
</div>
<?php if(User::isAdmin()): ?>
    <div class="fixed-action-btn">
        <a href="<?=Url::to(['variations/edit'])?>" class="btn-floating btn-large red tooltipped waves-effect waves-light" data-position="left" data-tooltip="Редактировать">
            <i class="large material-icons notranslate">mode_edit</i>
        </a>
    </div>
<?php endif; ?>