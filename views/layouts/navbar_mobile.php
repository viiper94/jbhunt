<?php use yii\helpers\Url; ?>

<div class="mobile-navbar">
    <ul id="slide-out" class="hide-on-large-only side-nav z-depth-5">
        <li>
            <?php $bg = Yii::$app->user->isGuest ? 'default.jpg' : Yii::$app->user->identity->bg_image ?>
            <div class="userView" style="background-image: url(<?= Yii::$app->request->baseUrl.'/images/users/bg/'. $bg . '?t='.time()?>); height: 160px;">
                <?php if(!Yii::$app->user->isGuest) : ?>
                    <a href="<?=Url::to(['site/profile'])?>"><img class="circle" src="<?=Yii::$app->request->baseUrl.'/images/users/'.Yii::$app->user->identity->picture.'?t='.time()?>"></a>
                    <a href="<?=Url::to(['site/profile'])?>">
                        <span class="white-text text-shadow name"><?=Yii::$app->user->identity->first_name?> <?=Yii::$app->user->identity->last_name?></span>
                    </a>
                    <a href="<?=Url::to(['site/profile'])?>">
                        <span class="white-text text-shadow email">
                            <?php if(Yii::$app->user->identity->company != '') : ?>
                                [<?=Yii::$app->user->identity->company?>]
                            <?php endif ?>
                            <?=Yii::$app->user->identity->nickname?>
                        </span>
                    </a>
                <?php endif ?>
            </div>
        </li>
        <li<?php if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id === 'index'){?> class="active"<?php } ?>>
            <a href="<?=Yii::$app->request->baseUrl?>/"><i class="material-icons notranslate">home</i>О НАС</a>
        </li>
        <li<?php if(Yii::$app->controller->action->id === 'rules'){?> class="active"<?php } ?>>
            <ul class="collapsible collapsible-accordion">
                <li>
                    <a class="collapsible-header waves-effect"><i class="material-icons notranslate">error</i>ПРАВИЛА</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="<?=Url::to(['site/rules'])?>">ПРАВИЛА КОМПАНИИ</a></li>
                            <li><a href="<?=Url::to(['site/variations', 'game' => 'ets2'])?>">ВАРИАЦИИ ГРУЗОВИКОВ</a></li>
                            <?php if(\app\models\User::isVtcMember()) : ?>
                                <li><a href="<?=Url::to(['site/exams'])?>">ЭКЗАМЕНЫ ДЛЯ ВОДИТЕЛЕЙ</a></li>
                            <?php endif ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li<?php if(Yii::$app->controller->id === 'convoys'){?> class="active"<?php } ?>>
            <a href="<?=Url::to(['convoys/index'])?>"><i class="material-icons notranslate">local_shipping</i>КОНВОИ</a>
        </li>
        <li<?php if(Yii::$app->controller->id === 'gallery'){?> class="active"<?php } ?>>
            <a href="<?=Url::to(['gallery/index'])?>"><i class="material-icons notranslate">collections</i>ГАЛЕРЕЯ</a>
        </li>
        <?php if(!\app\models\User::isVtcMember()): ?>
            <li<?php if(Yii::$app->controller->action->id === 'recruit'){?> class="active"<?php } ?>>
                <a href="<?=Url::to(['site/recruit'])?>"><i class="material-icons notranslate">contacts</i>ВСТУПИТЬ</a>
            </li>
        <?php endif ?>
        <li<?php if(Yii::$app->controller->action->id === 'modifications'){?> class="active"<?php } ?>>
            <ul class="collapsible collapsible-accordion">
                <li>
                    <a class="collapsible-header waves-effect"><i class="material-icons notranslate">settings</i>МОДИФИКАЦИИ</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="<?=Url::to(['modifications/index', 'game' => 'ets'])?>">ETS2MP</a></li>
                            <li><a href="<?=Url::to(['modifications/index', 'game' => 'ats'])?>">ATSMP</a></li>
                            <li><a href="<?=Url::to(['modifications/tedit'])?>">TEDIT</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <?php if(\app\models\User::isAdmin()) :
            $c_id = ['trailers', 'members', 'appeals'];
            $a_id = ['users']; ?>
            <li<?php if(in_array(Yii::$app->controller->id, $c_id) || in_array(Yii::$app->controller->action->id, $a_id)){?> class="active"<?php } ?>>
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header waves-effect"><i class="material-icons notranslate">view_module</i>УПРАВЛЕНИЕ</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?=Url::to(['members/index'])?>">СОТРУДНИКИ</a></li>
                                <li><a href="<?=Url::to(['members/stats'])?>">СТАТИСТИКА</a></li>
                                <li><a href="<?=Url::to(['site/users'])?>">ПОЛЬЗОВАТЕЛИ САЙТА</a></li>
                                <li><a href="<?=Url::to(['trailers/index'])?>">УПРАВЛЕНИЕ ПРИЦЕПАМИ</a></li>
                                <li><a href="<?=Url::to(['appeals/index'])?>">ЖАЛОБЫ</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        <?php else : ?>
            <li<?php if(Yii::$app->controller->id === 'members'){?> class="active"<?php } ?>>
                <a href="<?=Url::to(['members/index'])?>"><i class="material-icons notranslate">supervisor_account</i>ВОДИТЕЛИ</a>
            </li>
        <?php endif ?>
        <li<?php if(Yii::$app->controller->action->id === 'claims'){?> class="active"<?php } ?>>
            <a href="<?=Url::to(['claims/index'])?>"><i class="material-icons notranslate">receipt</i>ЗАЯВЛЕНИЯ</a>
        </li>
        <?php if(\app\models\User::isVtcMember()) : ?>
            <li<?php if(Yii::$app->controller->id === 'achievements'){?> class="active"<?php } ?>>
                <a href="<?=Url::to(['achievements/index'])?>"><i class="material-icons notranslate left">stars</i>ДОСТИЖЕНИЯ</a>
            </li>
        <?php endif ?>
        <?php if(Yii::$app->user->isGuest) : ?>
            <li<?php if(Yii::$app->controller->action->id === 'login'){?> class="active"<?php } ?>>
                <a href="<?=Url::to(['site/login'])?>"><i class="material-icons notranslate">exit_to_app</i>ВОЙТИ</a>
            </li>
        <?php else : ?>
            <li<?php if(Yii::$app->controller->action->id === 'profile'){?> class="active"<?php } ?>>
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header waves-effect"><i class="material-icons notranslate">person</i>ПРОФИЛЬ</a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?=Url::to(['site/profile'])?>">СМОТРЕТЬ ПРОФИЛЬ</a></li>
                                <li><a href="<?=Url::to(['site/profile', 'action' => 'edit'])?>">РЕДАКТИРОВАТЬ</a></li>
                                <li><a href="<?=Url::to(['site/logout'])?>">ВЫЙТИ</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        <?php endif ?>
    </ul>
</div>