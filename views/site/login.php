<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Войти на сайт - J.B. Hunt';
?>
<div class="parallax-container parallax-shadow" style="height: 400px;">
	<div class="container">
		<h4 class="parallax-title light white-text text-shadow">Вход на сайт</h4>
	</div>
	<div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/assets/img/login.jpg"></div>
</div>
<div class="container login-container">
	<div class="flex">
		<div class="card grey lighten-4 ">
			<?php $form = ActiveForm::begin([
				'fieldConfig' => [
					'template' => "<div class=\"input-field\">{label}{input}</div>"
				],
			]); ?>
			<div class="card-content">
				<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Логин или E-Mail') ?>
				<?= $form->field($model, 'password')->passwordInput()->label('Пароль')  ?>
				<?= $form->field($model, 'rememberMe', ['template' => '<div>{input}{label}</div>'])
					->checkbox(['label' => null])->error(false)->label('Запомнить') ?>
			</div>
			<div class="card-action">
				<?=Html::submitButton('Войти '.
					Html::tag('i', 'send', ['class' => 'material-icons notranslate right']), [
					'class' => 'btn indigo darken-3 waves-effect waves-light'
				])?>
				<a href="<?=Url::to(['site/signup'])?>" class="btn-flat waves-effect">
					<i class="material-icons notranslate right">add</i>Регистрация
				</a>
				<a href="#modal1" class="btn-flat waves-effect right modal-trigger">Забыли пароль?</a>
				<div class="clearfix"></div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
		<div class="card-panel grey lighten-4">
			<h5 class="card-title light">Вход через Steam</h5>
			<a href="<?= Url::to(['site/login', 'steam' => true]) ?>" class=""></a>
			<?= Html::beginForm(Url::to(['site/login', 'social' => 'steam'])) ?>
			<?= Html::submitButton('Войти через Steam', ['class' => 'steam-login btn green waves-effect text-shadow']) ?>
			<?= Html::endForm() ?>
		</div>
	</div>
</div>

<div id="modal1" class="modal reset-pwd">
    <div class="modal-content row">
        <h4 class="reset-pwd-title">Сброс пароля</h4>
        <p class="reset-pwd-text">Укажите свой E-Mail, и мы отправим Вам ссылку для сброса пароля.</p>
        <div class="input-field col s9">
            <input id="email" type="email" class="validate">
            <label for="email" data-error="Укажите правильный E-Mail" data-success="">Ваш E-Mail</label>
        </div>
        <div class="input-field col s2 preloader"></div>
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect btn-flat indigo-text text-darken-3">Закрыть</a>
        <a class="send-reset waves-effect btn-flat indigo-text text-darken-3">Далее</a>
    </div>
</div>
<?php if($model->hasErrors()) :
    //Kint::dump($model->errors)?>
    <script>
        <?php foreach ($model->errors as $error): ?>
        Materialize.toast('<?= $error[0] ?>', 6000);
        <?php endforeach; ?>
    </script>
<?php endif ?>