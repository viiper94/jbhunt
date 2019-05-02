<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Зарегистрированые пользователи сайта - J.B. Hunt'; ?>

<div class="container">
    <div class="card-panel grey lighten-4 search">
        <form method="get">
            <div class="input-field">
                <button type="submit" class="prefix user-search waves-effect circle">
                    <i class="material-icons notranslate">search</i>
                </button>
                <input placeholder="Искать пользователя" type="text" name="q" <?php if(Yii::$app->request->get('q')): ?>value="<?= Yii::$app->request->get('q') ?>"<?php endif ?>>
                <?php if(Yii::$app->request->get('q')) : ?>
                    <a href="<?= Url::to(['site/users']) ?>" class="search-reset waves-effect circle">
                        <i class="material-icons notranslate">clear</i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
	<div class="row">
		<h5 class="light col m6 s12">Всего <?= $total ?></h5>
		<?= LinkPager::widget([
			'pagination' => $pagination,
			'firstPageLabel' => 'Начало',
			'lastPageLabel' => 'Конец',
			'options' => [
				'class' => 'pagination right-align col m6 s12'
			],
			'prevPageCssClass' => 'waves-effect',
			'pageCssClass' => 'waves-effect',
			'nextPageCssClass' => 'waves-effect',
			'activePageCssClass' => 'active waves-effect',
			'disabledPageCssClass' => 'disabled',
			'maxButtonCount' => 5
		]) ?>
	</div>
    <?php foreach($users as $user): ?>
        <div class="card horizontal grey lighten-4 user hoverable">
            <div class="card-image no-img_horizontal" style="background-image: url(<?= Yii::$app->request->baseUrl ?>/web/images/users/<?= $user->picture ?>)">
                <a href="<?= Url::to(['site/profile', 'id' => $user->id]) ?>"
				   class="waves-effect waves-light <?php if(\app\models\User::isOnline($user)) : ?>online<?php endif ?>">
                </a>
            </div>
			<div class="card-stacked">
				<div class="card-content">
					<div class="user-info row">
						<div class="col l12 s12">
							<div class="col l5 s5 right-align"><span class="truncate" style="white-space: nowrap;"><b><?= $user->company != '' ? '['.$user->company.']' : '' ?></b></span></div>
							<div class="col l7 s7 profile-info"><span><b><?=$user->nickname?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align"><span>Имя:</span></div>
							<div class="col l7 s7 profile-info"><span><b><?=$user->first_name?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align"><span>Фамилия:</span></div>
							<div class="col l7 s7 profile-info"><span><b><?=$user->last_name?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align truncate"><span>Дата рождения:</span></div>
							<div class="col l7 s7 profile-info truncate"><span><b><?= \app\controllers\SiteController::getRuDate($user->birth_date) ?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align"><span>Страна:</span></div>
							<div class="col l7 s7 profile-info"><span><b><?=$user->country?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align"><span>Город:</span></div>
							<div class="col l7 s7 profile-info"><span><b><?=$user->city?></b></span></div>
						</div>
						<div class="col l12 s12">
							<div class="col l5 s5 right-align truncate"><span>Зарегестрирован:</span></div>
							<div class="col l7 s7 profile-info truncate"><span><b><?= \app\controllers\SiteController::getRuDate($user->registered) ?></b></span></div>
						</div>
					</div>
				</div>
				<?php if($user->vk || $user->steam || $user->truckersmp): ?>
					<div class="card-action">
						<?php if($user->vk){ ?>
							<a target="_blank" href="<?=$user->vk?>">ВКонтакте</a>
						<?php }
						if($user->steam){ ?>
							<a target="_blank" href="<?=$user->steam?>">Steam</a>
						<?php }
						if($user->truckersmp){ ?>
							<a target="_blank" href="<?=$user->truckersmp?>">TruckersMP</a>
						<?php } ?>
					</div>
				<?php endif ?>
			</div>
        </div>
    <?php endforeach; ?>
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => 'Начало',
        'lastPageLabel' => 'Конец',
        'options' => [
            'class' => 'pagination center'
        ],
        'prevPageCssClass' => 'waves-effect',
        'pageCssClass' => 'waves-effect',
        'nextPageCssClass' => 'waves-effect',
        'activePageCssClass' => 'active waves-effect',
        'disabledPageCssClass' => 'disabled',
        'maxButtonCount' => 5
    ]) ?>
</div>
