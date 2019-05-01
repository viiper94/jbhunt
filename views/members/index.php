<?php

use yii\helpers\Url;

$this->title = 'Сотрудники Volvo Trucks'; ?>

<div class="parallax-container parallax-shadow" style="height: 400px;">
    <div class="container">
        <h4 class="parallax-title light white-text text-shadow">Сотрудники Volvo Trucks</h4>
    </div>
    <div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/assets/img/members.jpg"></div>
</div>
<div class="container">
    <div class="row center">
        <a href="<?= Url::to(['members/stats']) ?>" class="btn btn-large indigo darken-3 waves-effect waves-light z-depth-3 light">
            <i class="material-icons notranslate left">grid_on</i>Смотреть таблицу статистики</a>
    </div>
    <?php foreach($all_members as $key => $member): ?>
        <div class="card horizontal grey lighten-4 hoverable">
            <div class="card-image no-img_horizontal" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/<?= $member->picture ?>)"></div>
            <div class="card-stacked">
                <div class="card-content">
                    <div class="card-title">
                        <?= $member->first_name . ' ' . $member->last_name ?>
                    </div>
                    <p class="grey-text">[Volvo Trucks] <?= $member->nickname ?></p>
                    <p>Дата рождения:
                        <span class="nowrap"><b><?= Yii::$app->formatter->asDate($member->birth_date, 'long') ?></b></span>
                        <span class="nowrap">(<?= \app\models\User::getUserAge($member->birth_date) ?>)</span>
                    </p>
                    <p>В компании с
                        <span class="nowrap"><b><?= Yii::$app->formatter->asDate($member->start_date, 'long') ?></b></span>
                        <span class="nowrap">(<?= $member->getMemberDays(); ?>)</span>
                    </p>
                </div>
                <div class="card-action">
                    <a href="<?= Url::to(['site/profile', 'id' => $member->user_id]) ?>" class="fs17">Профиль</a>
                    <?php if(\app\models\User::isAdmin()) : ?>
                        <a href="<?= Url::to(['members/edit', 'id' => $member->id]) ?>" class="fs17">Редактировать</a>
                        <a href="<?= Url::to(['members/resort', 'id' => $member->id, 'dir' => 'down']) ?>">
                            <i class="material-icons notranslate to-text">keyboard_arrow_down</i>
                        </a>
                        <a href="<?= Url::to(['members/resort', 'id' => $member->id, 'dir' => 'up']) ?>">
                            <i class="material-icons notranslate to-text">keyboard_arrow_up</i>
                        </a>
                    <?php endif ?>
                    <ul class="user-links right" style="width: 84px;">
                        <?php if($member->vk) : ?>
                            <li class="vk"><a class="waves-effect" target="_blank" href="<?= $member->vk ?>"></a></li>
                        <?php endif; ?>
                        <?php if($member->steam) : ?>
                            <li class="steam<?php if(!\app\models\User::isAdmin() && $member->visible_steam != 1):?> link-disabled<?php endif ?>">
                                <a class="waves-effect" <?php if(\app\models\User::isAdmin() || $member->visible_steam == 1):?> href="<?= $member->steam ?>"<?php endif ?> target="_blank"></a>
                            </li>
                        <?php endif; ?>
                        <?php if($member->truckersmp) : ?>
                            <li class="truckers-mp<?php if(!\app\models\User::isAdmin() && $member->visible_truckersmp != 1):?> link-disabled<?php endif ?>">
                                <a class="waves-effect"<?php if(\app\models\User::isAdmin() || $member->visible_truckersmp == 1):?> href="<?= $member->truckersmp ?>"<?php endif ?> target="_blank"></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>