<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Профиль - J.B. Hunt';
?>

<div class="container">
    <div class="card grey lighten-4">
        <div class="card-image no-img" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/bg/<?=$user->bg_image?>)"></div>
        <div class="card-content">
            <div class="profile-img z-depth-3 <?php if(\app\models\User::isOnline($user)) : ?>online<?php endif ?>" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/<?=$user->picture?>)"></div>
            <ul class="profile-links">
                <?php if($user->vk) : ?>
                    <li class="vk circle grey darken-3 z-depth-3"><a class="waves-effect waves-light circle" target="_blank" href="<?= $user->vk ?>"></a></li>
                <?php endif; ?>
                <?php if($user->steam) : ?>
                    <li class="steam circle grey darken-3 z-depth-3<?php if(!\app\models\User::isAdmin() && $user->visible_steam != 1):?> link-disabled<?php endif ?>">
                        <a class="waves-effect waves-light circle" <?php if(\app\models\User::isAdmin() || $user->visible_steam == 1):?> href="<?= $user->steam ?>"<?php endif ?> target="_blank"></a>
                    </li>
                <?php endif; ?>
                <?php if($user->truckersmp) : ?>
                    <li class="truckers-mp circle grey darken-3 z-depth-3<?php if(!\app\models\User::isAdmin() && $user->visible_truckersmp != 1):?> link-disabled<?php endif ?>">
                        <a class="waves-effect waves-light circle"<?php if(\app\models\User::isAdmin() || $user->visible_truckersmp == 1):?> href="<?= $user->truckersmp ?>"<?php endif ?> target="_blank"></a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="row">
                <div class="col s12 m6 l3 offset-l2 offset-m2 center-s">
                    <h4 class="card-title"><?=$user->first_name?> <?=$user->last_name?></h4>
                    <p class="grey-text"><?php if($user->isVtcMember()): ?>[J.B. Hunt] <?php endif ?><?=htmlentities($user->nickname) ?></p>
                </div>
                <div class="col s12 m4 l2 center">
                    <h4 class="card-title"><?= $user->age ?></h4>
                    <p class="grey-text truncate"><?= Yii::$app->formatter->asDate($user->birth_date, 'long') ?></p>
                </div>
                <div class="col s12 m6 l2 offset-m2 center-l center-s">
                    <h4 class="card-title truncate"><?=$user->city?></h4>
                    <p class="grey-text truncate"><?=$user->country?></p>
                </div>
                <div class="col s12 m4 l2 center">
                    <h4 class="card-title">
                        <?php if($user->has_ets == '0' && $user->has_ats == '0') echo 'Нет игр';
                        else if($user->has_ets == '0' && $user->has_ats == '1') echo 'ATS';
                        else if($user->has_ets == '1' && $user->has_ats == '0') echo 'ETS2';
                        else if($user->has_ets == '1' && $user->has_ats == '1') echo 'ETS2 и ATS'; ?>
                    </h4>
                    <p class="grey-text">Игры</p>
                </div>
                <div class="col s12 m1">

                </div>
            </div>
        </div>
        <?php if(!Yii::$app->user->isGuest && $user->id === Yii::$app->user->identity->id){?>
            <div class="card-action">
                <a href="<?=Url::to(['site/profile', 'action' => 'edit'])?>" class="indigo-text text-darken-3">Редактировать профиль</a>
                <a href="<?=Url::to(['site/logout'])?>" class="indigo-text text-darken-3">Выйти</a>
            </div>
        <?php } ?>
    </div>
</div>