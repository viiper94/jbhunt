<?php

use app\models\User;
use yii\helpers\Url;

$this->title = 'Вариации тягачей - J.B. Hunt'; ?>

<div class="container" style="margin-bottom: 15px;">
    <div class="row" style="margin-top: 20px;">
        <?php $game = Yii::$app->request->get('game'); ?>
        <div class="col m6 s12">
            <a href="<?= Url::to(['variations/index', 'game' => 'ets2']) ?>" class="btn-flat waves-effect right<?php if($game != 'ats'): ?> indigo-text bold<?php endif ?>">
                Отделение ETS2
            </a>
        </div>
        <div class="col m6 s12">
            <a href="<?= Url::to(['variations/index', 'game' => 'ats']) ?>" class="btn-flat waves-effect left<?php if($game == 'ats'): ?> indigo-text bold<?php endif ?>">
                Отделение ATS
            </a>
        </div>
    </div>
    <?php foreach($variations as $variation) : ?>
        <h5><?= $variation->name ?></h5>
        <div class="card grey lighten-4">
            <div class="card-image">
                <img class="responsive-img z-depth-2 materialboxed" src="<?= Yii::$app->request->baseUrl ?>/images/variations/<?= $variation->image ?? 'default.jpg' ?>">
            </div>
            <?php if($variation->description): ?>
                <ul class="card-collapsible collapsible" data-collapsible="accordion">
                    <li>
                        <div class="collapsible-header grey lighten-4"><i class="material-icons notranslate">arrow_forward</i>Описание</div>
                        <div class="collapsible-body grey lighten-4">
                            <?= $variation->description ?>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>
            <?php if(User::isAdmin()): ?>
                <div class="card-action">
                    <a href="<?=Url::to(['variations/edit', 'id' => $variation->id])?>">Редактировать</a>
                </div>
            <?php endif; ?>
        </div>
    <? endforeach; ?>
</div>
<?php if(\app\models\User::isAdmin()) : ?>
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light red" href="<?=Url::to(['variations/add'])?>"><i class="material-icons notranslate">add</i></a>
    </div>
<?php endif; ?>