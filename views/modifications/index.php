<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Моды для ETS2MP и ATSMP, Модификации для мультиплеера - ВТК Volvo Trucks';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Моды для Euro Truck Simulator 2 Multiplayer и American Truck Simulator Multiplayer. Моды для TruckersMP.'
]);
$this->registerMetaTag([
	'name' => 'keywords',
	'content' => 'моды для мп, ets2, ats, truckersmp, моды для ets2mp, моды для atsmp, как найти прицеп на конвой, как взять груз на конвой'
]); ?>

<div class="parallax-container parallax-shadow hide-on-small-only" style="height: 400px;">
	<div class="row">
		<div class="col m7 l9 offset-l3 offset-m5">
			<h4 class="parallax-title light white-text text-shadow col s12">
				<?= $subcategory->cat_title ?>
				<?php $cat_no_sub = ['all', 'ets', 'ats'];
				if(!in_array($subcategory->cat_name, $cat_no_sub)) : ?>
				    - <?= $subcategory->title ?>
				<?php endif ?>
			</h4>
		</div>
	</div>
	<div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/images/mods/categories/<?=$subcategory->cat_image?>"></div>
</div>

<div class="row">
	<div class="col l3 m5 s12" style="padding-bottom: 10px;">
		<div class="card-panel grey lighten-4 search">
			<form method="get">
				<div class="input-field">
					<button type="submit" class="prefix user-search waves-effect circle">
						<i class="material-icons notranslate">search</i>
					</button>
					<input placeholder="Поиск модификаций" type="text" name="q"
						   <?php if(Yii::$app->request->get('q')): ?>value="<?= Yii::$app->request->get('q') ?>"<?php endif ?>>
					<?php if(Yii::$app->request->get('q')) : ?>
						<a href="<?= Url::to(['modifications/index']) ?>" class="search-reset waves-effect">
							<i class="material-icons notranslate">clear</i>
						</a>
					<?php endif; ?>
				</div>
			</form>
		</div>
		<h5 class="light hide-on-small-and-down">Категории модификаций</h5>
        <div class="row hide-on-med-and-up">
            <div class="col s6">
                <a href="<?= Url::to(['modifications/index', 'game' => 'ets']) ?>"
                   class="col s12 btn-flat waves-effect waves-light center">ETS2</a>
            </div>
            <div class="col s6">
                <a href="<?= Url::to(['modifications/index', 'game' => 'ats']) ?>"
                   class="col s12 btn-flat waves-effect waves-light center">ATS</a>
            </div>
        </div>
		<ul class="collapsible cat-list hide-on-small-and-down" data-collapsible="expandable">
			<?php foreach($all_subcategories as $game => $categories) : ?>
				<li class="first-lvl">
					<div class="collapsible-header grey lighten-4<?php if($subcategory->for_ets === $game) : ?> active<?php endif ?>">
						<?= $game == 1 ?
							'Euro Truck Simulator 2' :
							'American Truck Simulator' ?>
					</div>
					<div class="collapsible-body grey lighten-4">
						<ul class="collapsible z-depth-0">
							<?php foreach(\yii\helpers\ArrayHelper::index($categories, null, 'cat_title') as $category => $sub_categories): ?>
								<li class="second-lvl">
									<div class="collapsible-header grey lighten-4<?php if($subcategory->cat_title == $category || $subcategory->cat_name == $game) : ?> active<?php endif ?>">
										<i class="material-icons left notranslate">keyboard_arrow_right</i>
										<?= $category ?>
									</div>
									<div class="collapsible-body grey lighten-4">
										<div class="collection">
											<?php foreach($sub_categories as $sub_cat): ?>
												<a href="<?= Url::to([
														'modifications/index',
														'game' => $game == 1 ? 'ets' : 'ats',
														'category' => $sub_cat['cat_name'],
														'subcategory' => $sub_cat['name']
												]) ?>" class="collection-item<?php if($subcategory->id == $sub_cat['id']) : ?> active<?php endif ?>">
													<?= $sub_cat['title'] ?>
												</a>
											<?php endforeach; ?>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
							<li>
								<?php $class = 'grey lighten-4';
								if($subcategory->for_ets === $game && $subcategory->cat_name == ($game == 1 ? 'ets' : 'ats')) $class = 'indigo darken-4 white-text'; ?>
								<a href="<?= Url::to(['modifications/index', 'game' => $game == 1 ? 'ets' : 'ats']) ?>"
								   class="collapsible-header <?= $class ?>">
									<i class="material-icons left notranslate">keyboard_arrow_right</i>
									Все моды для <?= $game == 1 ? 'ETS2' : 'ATS' ?>MP
								</a>
							</li>
						</ul>
					</div>
				</li>
			<?php endforeach ?>
			<li class="first-lvl">
				<a href="<?= Url::to(['modifications/index']) ?>" class="collapsible-header grey lighten-4 ">Все модификации</a>
			</li>
		</ul>

		<a class="btn-large green tooltipped waves-effect waves-light fs17" href="https://generator.volvovtc.com/" target="_blank" style="width: 100%;"
		   data-tooltip="Сгенерировать мод на прицеп" data-position="bottom">
			<i class="material-icons notranslate left">build</i>Генератор модификаций
		</a>
	</div>

	<div class="col l9 m7 s12">
		<?php if($mods) :
			foreach ($mods as $key => $mod):
				$class = $mod->visible == '1' ? 'grey' : 'yellow'; ?>

				<div class="col l4 m6 s12">
					<div class="card <?= $class ?> lighten-4 hoverable">

						<div class="card-image mod-img">
							<img class="materialboxed" width="100%"
								 src="<?=Yii::$app->request->baseUrl?>/images/<?= $mod->trailer ? 'trailers/'.$mod->tr_image : 'mods/'.$mod->picture ?>">
						</div>

						<div class="card-content mod-info">
							<?php if($mod->visible == '0') : ?>
								<p class="grey-text"><i class="material-icons notranslate left">visibility_off</i>Мод скрыт</p>
								<div class="clearfix"></div>
							<?php endif ?>
                            <?php if($dlc = unserialize($mod->dlc)) : ?>
                                <p class="grey-text">
                                    <?= \app\models\Convoys::getDLCString($dlc, 'Необходимо') ?>
                                </p>
                            <?php endif ?>
							<h6 class="fs17 mod-title"><b><?= $mod->title ?></b></h6>
							<?php if($mod->description) : ?>
								<div class="mod-description">
									<span><?= $mod->description ?></span>
								</div>
							<?php endif ?>
							<?php if($mod->warning) : ?>
								<div class="mod-warning">
									<span><i>(<?= $mod->warning ?>)</i></span>
								</div>
							<?php endif ?>
						</div>

						<div class="card-action mod-links">
							<a href="<?= \app\models\Mods::getModsPath($mod->game) . $mod->file_name ?>" class="waves-effect">Скачать
								<i class="material-icons notranslate left">get_app</i>
							</a>
							<?php if($mod->yandex_link) : ?>
								<a href="<?= $mod->yandex_link ?>" class="waves-effect">Яндекс.Диск</a>
							<?php endif ?>
							<?php if($mod->gdrive_link) : ?>
								<a href="<?= $mod->gdrive_link ?>" class="waves-effect">Google Drive</a>
							<?php endif ?>
							<?php if($mod->mega_link) : ?>
								<a href="<?= $mod->mega_link ?>" class="waves-effect">MEGA</a>
							<?php endif ?>

							<?php if(\app\models\User::isAdmin()) : ?>
								<a class='action-dropdown-button right' data-id="<?= $mod->id ?>"><i class="material-icons notranslate">more_vert</i></a>

								<ul id="action-dropdown-<?= $mod->id ?>" class='action-dropdown card-panel grey lighten-4'>
									<li class="clearfix">
										<a href="<?= Url::to(['modifications/edit', 'id' => $mod->id]) ?>" class="indigo-text">
											<i class="material-icons notranslate left">edit</i>Редактировать
										</a>
									</li>
									<li class="clearfix">
										<a onclick='return confirm("Удалить?")' href="<?= Url::to(['modifications/remove', 'id' => $mod->id]) ?>" class="indigo-text">
											<i class="material-icons notranslate left">delete</i>Удалить
										</a>
									</li>
									<li class="clearfix">
										<a href="<?= Url::to([$mod->visible == '1' ? 'modifications/hide' : 'modifications/show', 'id' => $mod->id]) ?>" class="indigo-text">
											<i class="material-icons notranslate left"><?= $mod->visible === 1 ? 'visibility' : 'visibility_off' ?></i>Спрятать/Показать
										</a>
									</li>
									<?php if(count($mods) > 1 && Yii::$app->request->get('subcategory')) { ?>
										<li class="divider"></li>
										<li class="clearfix">
											<a href="<?= Url::to(['modifications/sort', 'id' => $mod->id, 'dir' => 'up']) ?>" class="indigo-text">
												<i class="material-icons notranslate left">keyboard_arrow_up</i>Переместить выше
											</a>
										</li>
										<li class="clearfix">
											<a href="<?= Url::to(['modifications/sort', 'id' => $mod->id, 'dir' => 'down']) ?>" class="indigo-text">
												<i class="material-icons notranslate left">keyboard_arrow_down</i>Переместить ниже
											</a>
										</li>
									<?php } ?>
								</ul>
							<?php endif ?>
						</div>

					</div>
				</div>

				<?php if(($key - 2) % 3 == 0) : ?>
					<div class="clearfix-l"></div>
				<?php endif ?>
				<?php if($key % 2 != 0) : ?>
					<div class="clearfix-m"></div>
				<?php endif ?>

			<?php endforeach;
		else : ?>
			<div class="card-panel grey lighten-4">
				<h5 class="light">Пока что нет модов в этой категории =(</h5>
			</div>
		<?php endif ?>
		<p class="clearfix col s12 grey-text light">Нужна модификация для мультиплеера?
			<a href="https://vk.com/im?sel=-105444090" target="_blank" style="text-decoration: underline;">Пиши нам!</a>
		</p>
		<div class="clearfix"></div>
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

</div>

<?php if(\app\models\User::isAdmin()) : ?>
	<div class="fixed-action-btn fixed-action-btn">
		<a href="<?=Url::to(['modifications/add'])?>" class="btn-floating btn-large waves-effect waves-light red">
			<i class="material-icons notranslate">add</i>
		</a>
	</div>
<?php endif ?>