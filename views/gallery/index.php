<?php

use yii\helpers\Url;

$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/lightGallery/css/lightgallery.min.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/lightGallery/js/lightgallery-all.min.js',
	['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = 'Галерея';
?>

<div class="row" id="lightgallery">
	<?php foreach($photos as $key => $photo) : ?>
		<div class="item col s<?php if($key !== 0): ?>6 m4 l3<? else: ?>12 first<?php endif?> <?php if($photo->visible == '0'):?>photo-hidden<?php endif ?>" style="position: relative;">
			<div data-src="<?= Yii::$app->request->baseUrl ?>/images/gallery/<?= $photo->image_original ?>"
				 style="background-image: url(<?= Yii::$app->request->baseUrl ?>/images/gallery/<?= $photo->image ?>)"
				 class="photo">
				<img src="<?= Yii::$app->request->baseUrl ?>/images/gallery/<?= $photo->image ?>" class="thumb" alt="<?= $photo->description ?>">
			</div>
			<?php if(\app\models\User::isAdmin()) : ?>
				<div class="controls" style="position:absolute; right: 10px; top: 10px;">
					<a class='action-dropdown-button right text-shadow' data-id="<?= $photo->id ?>" style="color: #fff;">
						<i class="material-icons notranslate">more_vert</i>
					</a>
					<ul id="action-dropdown-<?= $photo->id ?>" class='action-dropdown card-panel grey lighten-4'>
						<li class="clearfix">
							<a href="<?= Url::to([
								'gallery/' . ($photo->visible == '0' ? 'show' : 'hide'),
								'id' => $photo->id
							]) ?>" class="indigo-text">
								<i class="material-icons notranslate left"><?= $photo->visible == '0' ? 'visibility' : 'visibility_off' ?></i>
								<?= $photo->visible == '0' ? 'Показать' : 'Спрятать' ?>
							</a>
						</li>
						<li class="clearfix">
							<a onclick='return confirm("Удалить?")' href="<?= Url::to(['gallery/remove', 'id' => $photo->id]) ?>" class="indigo-text">
								<i class="material-icons notranslate left">delete</i>Удалить
							</a>
						</li>
						<?php if(count($photos) > 1) { ?>
							<li class="divider"></li>
							<li class="clearfix">
								<a href="<?= Url::to(['gallery/sort', 'id' => $photo->id, 'operation' => 'up']) ?>" class="indigo-text">
									<i class="material-icons notranslate left">keyboard_arrow_up</i>Переместить выше
								</a>
							</li>
							<li class="clearfix">
								<a href="<?= Url::to(['gallery/sort', 'id' => $photo->id, 'operation' => 'down']) ?>" class="indigo-text">
									<i class="material-icons notranslate left">keyboard_arrow_down</i>Переместить ниже
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>

<?php if(\app\models\User::isAdmin() || \app\models\User::isVtcMember()) : ?>

	<div class="fixed-action-btn">
		<a class="btn-floating btn-large red waves-effect waves-light modal-trigger" href="#gallery-image-upload-modal">
			<i class="large material-icons">add</i>
		</a>
	</div>

	<div id="gallery-image-upload-modal" class="modal">
		<div class="modal-content">
			<h4 class="light center">Загрузка изображения в галерею</h4>
			<div class="row">
				<div class="file-field input-field">
					<div class="btn indigo darken-3">
						<span>Выберите файл</span>
						<input type="file" id="gallery-image" class="validate-img-size" data-maxsize="15000000" data-alert="Максимальный размер файла 15Мб">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" placeholder="Максимальный размер 15Мб" readonly>
					</div>
				</div>
				<div class="input-field col s12">
					<textarea id="gallery-description" class="materialize-textarea"></textarea>
					<label for="gallery-description">Добавьте описание</label>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" id="gallery-send-btn" class="modal-action waves-effect waves-light btn indigo darken-3" data-uid="<?= Yii::$app->user->id ?>">
				<i class="material-icons notranslate left">send</i>Загрузить
			</a>
			<a href="#!" class="modal-action modal-close waves-effect btn-flat">Отмена</a>
		</div>
	</div>

<?php endif ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#lightgallery').lightGallery({
			thumbnail : true,
			selector: '.photo'
		});
	});
</script>