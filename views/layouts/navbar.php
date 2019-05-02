<?php use yii\helpers\Url; ?>

<div class="navbar-fixed">
    <nav class="white">
        <div class="nav-wrapper">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons notranslate">menu</i></a>
            <a href="<?= Yii::$app->request->baseUrl?>/" class="brand-logo" style="position: relative;">
                <img src="<?=Yii::$app->request->baseUrl?>/assets/img/volvo-sign.png" alt="J.B. Hunt">
            </a>
            <ul id="nav-mobile" class="hide-on-med-and-down right">
                <li<?php if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id === 'index'){?> class="active"<?php } ?>>
                    <a href="<?=Yii::$app->request->baseUrl?>/">О НАС</a>
                </li>
                <li<?php if(Yii::$app->controller->action->id === 'rules'){?> class="active"<?php } ?> id="rules-btn">
                    <a href="<?=Url::to(['site/rules'])?>">ПРАВИЛА</a>
                    <ul id="rules-dropdown" class="z-depth-2">
                        <li>
                            <a href="<?=Url::to(['site/variations', 'game' => 'ets2'])?>">
                                <i class="material-icons notranslate left">lightbulb_outline</i>ВАРИАЦИИ ТЯГАЧЕЙ
                            </a>
                        </li>
                        <?php if(\app\models\User::isVtcMember()) : ?>
                            <li>
                                <a href="<?=Url::to(['site/exams'])?>">
                                    <i class="material-icons notranslate left">filter_list</i>ЭКЗАМЕНЫ
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </li>
                <li<?php if(Yii::$app->controller->id === 'convoys'){?> class="active"<?php } ?>><a href="<?=Url::to(['convoys/index'])?>">КОНВОИ</a></li>
                <li<?php if(Yii::$app->controller->id === 'gallery'){?> class="active"<?php } ?>><a href="<?=Url::to(['gallery/index'])?>">ГАЛЕРЕЯ</a></li>
                <?php if(!\app\models\User::isVtcMember()): ?>
                    <li<?php if(Yii::$app->controller->action->id === 'recruit'){?> class="active"<?php } ?>><a href="<?=Url::to(['site/recruit'])?>">ВСТУПИТЬ</a></li>
                <?php endif ?>
                <li<?php if(Yii::$app->controller->id === 'mods'){?> class="active"<?php } ?> id="mods-btn">
                    <a href="<?=Url::to(['modifications/index'])?>">МОДЫ</a>
					<ul id="mods-dropdown" class="z-depth-2">
                        <li><a href="<?=Url::to(['modifications/index', 'game' => 'ets'])?>">ETS2MP</a></li>
                        <li><a href="<?=Url::to(['modifications/index', 'game' => 'ats'])?>">ATSMP</a></li>
                        <li><a href="<?=Url::to(['modifications/tedit'])?>">TEDIT</a></li>
						<?php if(\app\models\User::isAdmin()) : ?>
							<li><a href="<?=Url::to(['modifications/add'])?>">ДОБАВИТЬ МОД</a></li>
						<?php endif ?>
                    </ul>
                </li>
                <?php if(\app\models\User::isAdmin()) :
                    $c_id = ['trailers', 'members', 'appeals'];
                    $a_id = ['users']; ?>
                    <li id="manage-btn"<?php if(in_array(Yii::$app->controller->id, $c_id) || in_array(Yii::$app->controller->action->id, $a_id)){?> class="active"<?php } ?>>
                        <a href="<?=Url::to(['members/stats'])?>">УПРАВЛЕНИЕ</a>
                        <ul id="manage-dropdown" class="z-depth-2">
                            <li><a href="<?=Url::to(['members/index'])?>"><i class="material-icons notranslate left">supervisor_account</i>СОТРУДНИКИ</a></li>
                            <li><a href="<?=Url::to(['members/stats'])?>"><i class="material-icons notranslate left">insert_chart</i>СТАТИСТИКА</a></li>
                            <li><a href="<?=Url::to(['site/users'])?>"><i class="material-icons notranslate left">people</i>ПОЛЬЗОВАТЕЛИ САЙТА</a></li>
                            <li><a href="<?=Url::to(['trailers/index'])?>"><i class="material-icons notranslate left">local_shipping</i>УПРАВЛЕНИЕ ПРИЦЕПАМИ</a></li>
                            <li><a href="<?=Url::to(['appeals/index'])?>"><i class="material-icons notranslate left">info</i>ЖАЛОБЫ</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li<?php if(Yii::$app->controller->action->id === 'members'){?> class="active"<?php } ?>><a href="<?=Url::to(['site/members'])?>">ВОДИТЕЛИ</a></li>
                <?php endif ?>
                <li<?php if(Yii::$app->controller->action->id === 'claims'){?> class="active"<?php } ?>><a href="<?=Url::to(['claims/index'])?>">ЗАЯВЛЕНИЯ</a></li>
                <?php if(\app\models\User::isVtcMember()) : ?>
                    <li<?php if(Yii::$app->controller->id === 'achievements'){?> class="active"<?php } ?>><a href="<?=Url::to(['achievements/index'])?>">ДОСТИЖЕНИЯ</a></li>
                <?php endif ?>
                <?php if(Yii::$app->user->isGuest) : ?>
                    <li<?php if(Yii::$app->controller->action->id === 'login'){?> class="active"<?php } ?>><a href="<?=Url::to(['site/login'])?>">ВОЙТИ</a></li>
                <?php else : ?>
                    <li<?php if(Yii::$app->controller->action->id === 'profile'){?> class="active"<?php } ?> id="profile-btn">
                        <a href="<?=Url::to(['site/profile'])?>">ПРОФИЛЬ</a>
                        <ul id="profile-dropdown" class="z-depth-2">
                            <li><a href="<?=Url::to(['site/profile', 'action' => 'edit'])?>"><i class="material-icons notranslate left">settings</i>РЕДАКТИРОВАТЬ</a></li>
                            <li><a href="<?=Url::to(['site/logout'])?>"><i class="material-icons notranslate left">exit_to_app</i>ВЫЙТИ</a></li>
                        </ul>
                    </li>
                    <li id="notification-item" class="notification-btn-item">
                        <a class="notification-btn">
                            <i class="material-icons notranslate">notifications</i>
                            <?php if(Yii::$app->user->identity->notifications && Yii::$app->user->identity->has_unread_notifications) : ?>
                                <div class="new-notifications z-depth-2 green"></div>
                            <?php endif ?>
                        </a>
                        <ul class="notification-list z-depth-3">
                            <?php if(Yii::$app->user->identity->notifications && count(Yii::$app->user->identity->notifications) > 0):
                                foreach(Yii::$app->user->identity->notifications as $notification): ?>
                                    <li class="flex<?php if($notification->status == '0'): ?> unread-notification<?php endif ?>" data-id="<?= $notification->id ?>" style="justify-content: space-between">
                                        <span class="truncate"><?= $notification->text ?></span>
                                        <a class="clear-notification right tooltipped " data-position="left" data-tooltip="Скрыть"><i class="material-icons notranslate">clear</i></a>
                                    </li>
                                <?php endforeach;
                            else: ?>
                                <li>Нет уведомлений!</li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
</div>