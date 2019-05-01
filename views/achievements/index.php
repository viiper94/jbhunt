<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Достижения - Volvo Trucks';
?>

<div class="parallax-container parallax-shadow" style="height: 300px;">
    <div class="container">
        <h4 class="parallax-title light white-text text-shadow">Достижения</h4>
    </div>
    <div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/assets/img/achievements.jpg"></div>
</div>
<div class="container">
    <?php if(count($achievements) > 0) { ?>
		<div class="row">
			<div class="col m6 s12">
				<div class="card-panel grey lighten-4 search">
					<form method="get">
						<div class="input-field">
							<button type="submit" class="prefix user-search waves-effect circle">
								<i class="material-icons notranslate">search</i>
							</button>
							<input placeholder="Искать достижение" type="text" name="q" <?php if(Yii::$app->request->get('q')): ?>value="<?= Yii::$app->request->get('q') ?>"<?php endif ?>>
							<?php if(Yii::$app->request->get('q')) : ?>
								<a href="<?= Url::to(['achievements/index']) ?>" class="search-reset waves-effect circle">
									<i class="material-icons notranslate">clear</i>
								</a>
							<?php endif; ?>
						</div>
					</form>
				</div>
			</div>
			<?= LinkPager::widget([
				'pagination' => $pagination,
				'firstPageLabel' => 'Начало',
				'lastPageLabel' => 'Конец',
				'options' => [
					'class' => 'pagination center col m6 s12'
				],
				'prevPageCssClass' => 'waves-effect',
				'pageCssClass' => 'waves-effect',
				'nextPageCssClass' => 'waves-effect',
				'activePageCssClass' => 'active waves-effect',
				'disabledPageCssClass' => 'disabled',
				'maxButtonCount' => 5
			]) ?>
		</div>
        <div class="row">
            <?php $i =  0;
            foreach ($achievements as $key => $achievement):
				// show if no relation
				// show if has relation and user complete related achievement
				// show if user is admin
                if(!$achievement->related || $achievement->related && ($user_complete_ach && in_array($achievement->related, $user_complete_ach)) || \app\models\User::isAdmin()):
                    $progress_percent = 0;
                    $card_color = 'grey lighten-4';
                    $completed = false;
                    $i++;
                    if($user_complete_ach && in_array($achievement->id, $user_complete_ach)){
                        $card_color = 'green lighten-2';
                        $completed = true;
                    }
                    $progress = 0;
					$count = 0;
                    if(!$completed){
                        foreach ($user_ach_progress as $ach){
                            if($achievement->id == $ach['ach_id']){
								$ach['complete'] == 1 ? $progress++ : $count++;
							}
                        }
                        if($progress > 0) $progress_percent = $progress / $achievement->progress * 100;
                    } ?>
                    <div class="col s12 m6 l4">
                        <div class="card <?= $card_color ?> hoverable">
                            <div class="card-image">
                                <?php if($progress_percent > 0) : ?>
                                    <div class="progress" style="margin: 0;">
                                        <div class="determinate" style="width: <?= $progress_percent ?>%"></div>
                                    </div>
                                <?php endif;?>
                                <?php if($achievement->related && \app\models\User::isAdmin()): ?>
                                    <div class="achievement-related">
										<i class="material-icons notranslate"
										   title="Зависит от достижения &quot;<?= $achievement->r_title ?>&quot;">redo</i>
									</div>
                                <?php endif ?>
                                <?php if($completed): ?>
                                    <div class="complete-achievement flex">
                                        <i class="material-icons notranslate green-text large text-shadow">check</i>
                                    </div>
                                <?php endif ?>
                                <img src="<?= Yii::$app->request->baseUrl ?>/images/achievements/<?= $achievement->image ? $achievement->image : 'default.jpg' ?>">
                                <div class="card-title text-shadow"><?= $achievement->title ?></div>
                            </div>
                            <div class="card-content">
                                <p><?= $achievement->description ?></p>
								<?php if($progress_percent > 0) : ?>
									<p class="grey-text">Ход выполнения: <?= $progress ?>/<?= $achievement->progress ?></p>
								<?php endif;?>
								<?php if($count > 0) : ?>
								    <p class="grey-text">Скриншотов на модерации: <?= $count ?></p>
								<?php endif ?>
                            </div>
                            <div class="card-action" style="position: relative; min-height: 55px;">
                                <?php if(!$completed) : ?>
                                    <a href="#modal1" class="modal-trigger get-achievement" data-id="<?= $achievement->id ?>" data-title="<?= $achievement->title ?>">Выполнить</a>
                                <?php endif ?>
                                <?php if(\app\models\User::isAdmin()) : ?>
                                    <a class='action-dropdown-button right' data-id="<?= $achievement->id ?>"><i class="material-icons notranslate">more_vert</i></a>
                                    <ul id="action-dropdown-<?= $achievement->id ?>" class='action-dropdown card-panel grey lighten-4'>
                                        <li class="clearfix">
                                            <a href="<?= Url::to(['achievements/edit', 'id' => $achievement->id]) ?>" class="indigo-text">
                                                <i class="material-icons notranslate left">edit</i>Редактировать
                                            </a>
                                        </li>
                                        <li class="clearfix">
                                            <a onclick='return confirm("Удалить?")' href="<?= Url::to(['achievements/remove', 'id' => $achievement->id]) ?>" class="indigo-text">
                                                <i class="material-icons notranslate left">delete</i>Удалить
                                            </a>
                                        </li>
                                        <?php if(count($achievements) > 1) { ?>
                                            <li class="divider"></li>
                                            <li class="clearfix">
                                                <a href="<?= Url::to(['achievements/sort', 'id' => $achievement->id, 'operation' => 'up']) ?>" class="indigo-text">
                                                    <i class="material-icons notranslate left">keyboard_arrow_up</i>Переместить выше
                                                </a>
                                            </li>
                                            <li class="clearfix">
                                                <a href="<?= Url::to(['achievements/sort', 'id' => $achievement->id, 'operation' => 'down']) ?>" class="indigo-text">
                                                    <i class="material-icons notranslate left">keyboard_arrow_down</i>Переместить ниже
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
					<?php if($i % 3 == 0) : ?>
						<div class="clearfix-l"></div>
					<?php endif ?>
					<?php if($i % 2 == 0) : ?>
						<div class="clearfix-m"></div>
					<?php endif ?>
            <?php endif;
            endforeach; ?>
        </div>
        <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => 'Начало',
        'lastPageLabel' => 'Конец',
        'options' => [
            'class' => 'pagination center col m6 s12'
        ],
        'prevPageCssClass' => 'waves-effect',
        'pageCssClass' => 'waves-effect',
        'nextPageCssClass' => 'waves-effect',
        'activePageCssClass' => 'active waves-effect',
        'disabledPageCssClass' => 'disabled',
        'maxButtonCount' => 5
    ]) ?>
        <div id="modal1" class="modal">
            <div class="modal-content container">
                <h5 class="light">Выполнение достижения "<span class="ach-modal-title"></span>"</h5>
                <div class="file-field input-field">
                    <div class="btn indigo darken-3">
                        <span>Загрузить скриншот</span>
                        <input type="file">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                <h6 class="light fs17 grey-text">Как выполнять достижения с грузами?</h6>
                <ol class="grey-text">
                    <li>Достижения должны выполняться ТОЛЬКО в мультиплеере.</li>
                    <li>Когда припарковали прицеп с грузом, перед его отциплением нажмите TAB. Убедитесь, что окно "таба" открылось и в нём видно ваш игровой ник.</li>
                    <li>Отцепляйте прицеп, дождитесть пока на экране появиться результат доставки груза.</li>
                    <li>Сделайте скриншот (кнопка F10 по-умолчанию). Он сохранится по пути: <i>C:\Users\*Ваш пользователь*\Documents\Euro Truck Simulator 2\screenshot</i></li>
                    <li>Выберите сделанный скриншот в форме выше и нажмите кнопку "Выполнить".</li>
                </ol>
            </div>
            <div class="modal-footer">
                <a class="modal-action waves-effect btn-flat" id="get-ach" data-uid="<?= Yii::$app->user->id ?>" data-achid="0">Выполнить</a>
                <a class="modal-action modal-close waves-effect btn-flat">Закрыть</a>
            </div>
        </div>
    <?php }else{ ?>
        <h5 class="light">Нет достижений</h5>
    <?php } ?>
    <?php if(\app\models\User::isAdmin()): ?>
        <div class="fixed-action-btn tooltipped" data-position="left" data-tooltip="Новое достижение">
            <a href="<?=Url::to(['achievements/add'])?>" class="btn-floating btn-large waves-effect waves-light red">
                <i class="material-icons notranslate">add</i>
            </a>
        </div>
        <div class="fixed-action-btn tooltipped" style="margin-bottom: 71px" data-position="left" data-tooltip="Модерация достижений">
            <a href="<?=Url::to(['achievements/moderate'])?>" class="btn-floating btn-large waves-effect waves-light green">
                <i class="material-icons notranslate">check</i>
                <?php if($moderate_count > 0) : ?>
                    <span class="moderate-count white-text"><?= $moderate_count ?></span>
                <?php endif ?>
            </a>
        </div>
    <?php endif ?>
</div>