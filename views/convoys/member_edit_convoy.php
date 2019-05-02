<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->controller->action->id == 'add' ? 'Добавить конвой' : 'Редактировать конвой' ;
$this->title .= $model->game == 'ets' ? ' по ETS2' : ' по ATS';
$this->title .= ' - J.B. Hunt';
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/admin.js?t='.time(),  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/cities.js?t='.time(),  ['position' => yii\web\View::POS_END]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/select2.min.js',  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2-custom.css?t='.time());
$this->registerJsFile(Yii::$app->request->baseUrl.'/lib/ck-editor/ckeditor.js?t='.time(),  ['position' => yii\web\View::POS_HEAD]);
?>

<div class="container">
	<div class="row" style="margin-top: 20px;">
		<div class="col m6 s12 right-align">
			<a href="<?= Url::to(['convoys/add', 'game' => 'ets']) ?>" class="btn-flat waves-effect<?php if($model->game == 'ets'): ?> disabled<?php endif ?>">
				Добавить конвой по ETS2</a>
		</div>
		<div class="col m6 s12">
			<a href="<?= Url::to(['convoys/add', 'game' => 'ats']) ?>" class="btn-flat waves-effect<?php if($model->game == 'ats'): ?> disabled<?php endif ?>">
				Добавить конвой по ATS</a>
		</div>
	</div>
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<h5 class="light">Карта маршрута</h5>
	<div class="card grey lighten-4">
		<div class="card-image" style="max-height: 350px;">
			<img src="<?=Yii::$app->request->baseUrl?>/assets/img/no_route.jpg" class="responsive-img" id="preview">
		</div>
		<div class="card-content">
			<div class="picture-full">
				<label>Оригинальная карта маршрута (макс. 15 Мб)</label>
				<div class="file-field">
					<div class="btn indigo darken-3 waves-effect waves-light">
						<span>Загрузить изображение</span>
						<?= $form->field($model, 'picture_full')->fileInput([
							'class' => 'convoy-validate-img-size',
							'data-maxsize' => '15000000',
							'data-alert' => 'Максимальный размер файла 15Мб',
						]) ?>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" readonly="readonly" value="<?= $model->picture_full ?>">
					</div>
				</div>
			</div>
			<div class="picture-small" style="display: none">
				<label>Уменьшенная карта маршрута (макс. 5 Мб)</label>
				<div class="file-field">
					<div class="btn indigo darken-3 waves-effect waves-light">
						<span>Загрузить изображение</span>
						<?= $form->field($model, 'picture_small')->fileInput([
							'class' => 'validate-img-size',
							'data-maxsize' => '5000000',
							'data-alert' => 'Максимальный размер файла 5Мб',
						]) ?>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" readonly="readonly">
					</div>
				</div>
			</div>
			<div class="center">
				<?php if($model->game == 'ets') : ?>
					<?= $form->field($model, 'dlc[Going East!]', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Going East!') ?>
					<?= $form->field($model, 'dlc[Scandinavia]', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Scandinavia') ?>
					<?= $form->field($model, 'dlc[Vive La France!]', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Vive La France!') ?>
					<?= $form->field($model, 'dlc[Italia]', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Italia') ?>
				<?php else : ?>
					<?= $form->field($model, 'dlc[New Mexico]', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('New Mexico') ?>
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col l6 s12">
			<h5 class="light">Сборы</h5>
			<div class="card-panel grey lighten-4">
				<?= $form->field($model, 'date')
					->input('date', [
						'class' => 'datepicker-member-convoy',
						'data-value' => $model->date
					])
					->error(false) ?>
				<?= $form->field($model, 'meeting_time')->input('time', ['class' => 'timepicker'])->error(false) ?>
				<?= $form->field($model, 'departure_time')->input('time', ['class' => 'timepicker'])->error(false) ?>
				<?= $form->field($model, 'server')->dropdownList($servers)->error(false) ?>
				<div class="input-field">
					<?= $form->field($model, 'communications')->textInput(['readonly' => 'readonly'])->error(false) ?>
				</div>
			</div>
		</div>
		<div class="col l6 s12">
			<h5 class="light">Маршрут</h5>
			<div class="card-panel grey lighten-4">
				<?php $class = 'autocomplete';
					if($model->game == 'ats') $class .= '-ats'; ?>
				<div class="input-field">
					<?= $form->field($model, 'start_city')->textInput(['class' => $class.'-city', 'autocomplete' => 'off'])->error(false) ?>
				</div>
				<div class="input-field">
					<?= $form->field($model, 'start_company')->textInput(['class' => $class.'-company', 'autocomplete' => 'off'])->error(false) ?>
				</div>
				<div class="input-field">
					<?= $form->field($model, 'finish_city')->textInput(['class' => $class.'-city', 'autocomplete' => 'off'])->error(false) ?>
				</div>
				<div class="input-field">
					<?= $form->field($model, 'finish_company')->textInput(['class' => $class.'-company', 'autocomplete' => 'off'])->error(false) ?>
				</div>
				<div class="input-field">
					<?= $form->field($model, 'rest')->textInput()->error(false) ?>
				</div>
				<div class="input-field">
					<?= $form->field($model, 'length')->textInput()->error(false) ?>
				</div>
			</div>
		</div>
		<h5 class="clearfix light col s12">Вариация и грузовик</h5>
		<div class="col l6 s12">
			<div class="card-panel grey lighten-4">
				<div class="input-field">
					<?= $form->field($model, 'truck_var', ['template' => '{input}{label}'])
						->dropdownList($model->convoy->getVariationsByGame())
						->error(false) ?>
				</div>
				<div style="padding-bottom: 20px;">
					<label class="control-label">Прицеп</label>
					<?= $form->field($model, 'trailer')
						->dropdownList($trailers, [
							'id' => 'trailer-select',
							'class' => 'browser-default trailers-select',
							'data-target' => 'trailers'])
						->error(false)
						->label(false) ?>
				</div>
				<label>Дополнительное изображение (если необходимо) (макс. 15 Мб)</label>
				<div class="file-field">
					<div class="btn indigo darken-3 waves-effect waves-light">
						<i class="material-icons notranslate left">add</i>
						<span>Загрузить</span>
						<?= $form->field($model, 'extra_picture')->fileInput([
							'class' => 'validate-img-size',
							'data-maxsize' => '15000000',
							'data-alert' => 'Максимальный размер файла 15Мб',
						])->label(false)->error(false) ?>
					</div>
					<div class="file-path-wrapper">
						<input class="file-path" type="text" value="<?= $model->extra_picture ?>" readonly="readonly">
					</div>
				</div>
			</div>
		</div>
		<div class="col l6 s12">
			<div class="card trailer-info">
				<div class="card-image" id="trailer-info">
					<img src="<?= Yii::$app->request->baseUrl . '/images/trailers/' . ($model->tr_image ? $model->tr_image : 'default.jpg') ?>"
						 class="responsive-img z-depth-2" id="trailer-image" style="max-height: 304px; object-fit: cover">
				</div>
			</div>
		</div>
		<div class="col s12">
			<h5 class="light">Описание</h5>
			<div class="input-field file-field" style="margin-bottom: 15px;">
				<?= $form->field($model, 'add_info')->textarea(['class' => 'materialize-textarea', 'id' => 'add_info'])->label(false) ?>
			</div>
		</div>
		<div class="col s12">
			<div class="card-panel grey lighten-4">
				<h5 class="light">
					<i class="material-icons notranslate small left">info</i>
					После сохранения, конвой будет отправлен на модерацию и
					появится в разделе <a href="<?= Url::to(['convoys/index']) ?>">Конвои</a> после одобрения одним из администраторов.
				</h5>
			</div>
		</div>
		<div class="fixed-action-btn">
			<?=Html::submitButton(Html::tag('i', 'save', [
				'class' => 'large material-icons notranslate'
			]), ['class' => 'btn-floating btn-large red waves-effect waves-light']) ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
	CKEDITOR.replace('add_info');
	$('#trailer-select').select2();
</script>