<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/select2.min.js?t='.time(),  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2.min.css?t='.time());
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2-custom.css?t='.time());
$this->title = 'Вступить в J.B. Hunt'; ?>

<div class="container">

    <?php if($step == '1') : ?>

        <div class="card grey lighten-4">
            <div class="card-image no-img" style="background-image: url(<?= Yii::$app->request->baseUrl ?>assets/img/recruit/recruit.jpg)">
                <span class="recruit-text-f text-shadow">Вступление в компанию</span>
                <span class="recruit-text-s text-shadow">Шаг 1</span>
            </div>
            <div class="card-content">
                <h5>Перед подачей заявки убедитесь, что:</h5>
                <ol>
                    <li>Ваш профиль Steam открыт для всех <a href="http://steamcommunity.com/profiles/76561198283160497/edit/settings" target="_blank">Открыть</a></li>
                    <li>Ваши личные сообщения ВК открыты для всех <a href="https://vk.com/settings?act=privacy" target="_blank">Открыть</a></li>
                    <li>Ваши профиль на сайте полностью заполнен <a href="<?=Url::to(['site/profile', 'action' => 'edit'])?>" target="_blank">Заполнить</a></li>
<!--                    <li>Вам есть 18 лет. Если Вам нет 18, <a href="https://vk.com/im?sel=105513579" target="_blank">писать сюда</a></li>-->
<!--                    <li>Вы ознакомились с <a href="--><?//= Url::to(['site/rules']) ?><!--" target="_blank">правилами</a></li>-->
                    <li>Вы не состоите в другой ВТК</li>
                </ol>
            </div>
            <div class="card-action">
                <a href="<?= Url::to(['site/recruit', 'step' => '2']) ?>" class="btn indigo darken-3 waves-effect waves-light">
					Далее<i class="material-icons notranslate right">arrow_forward</i>
				</a>
            </div>
        </div>

    <?php endif ?>

    <?php if($step == '2') : ?>

        <div class="card grey lighten-4">
            <div class="card-image no-img" style="background-image: url(<?= Yii::$app->request->baseUrl ?>assets/img/recruit/recruit-2.jpg)">
                <span class="recruit-text-f text-shadow">Вступление в компанию</span>
                <span class="recruit-text-s text-shadow">Шаг 2</span>
            </div>
            <div class="card-content">
                <h5>Прежде, чем подавать заявку на вступление, хорошо прочитайте все правила!</h5>
                <div class="step-2-rules card-panel grey lighten-5">
                    <?= $rules->text ?>
                </div>

                <div><?=Html::checkbox('fulfill', false, ['id' => 'fulfill']).Html::label('Я выполнил все требования', 'fulfill')?></div>
                <div><?=Html::checkbox('read-rules', false, ['id' => 'read-rules']).Html::label('Я ознакомлен с правилами', 'read-rules')?></div>

            </div>
            <div class="card-action">
                <a href="<?= Url::to(['site/recruit', 'step' => '1']) ?>" class="btn indigo darken-3 waves-effect waves-light">
					<i class="material-icons notranslate left">arrow_back</i>Назад
				</a>
                <a href="<?= Url::to(['site/recruit', 'step' => '3']) ?>" class="btn indigo darken-3 waves-effect waves-light disabled" id="recruit-btn">
					Далее<i class="material-icons notranslate right">arrow_forward</i>
				</a>
            </div>
        </div>

    <?php endif ?>

    <?php if($step == '3') : ?>

        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "<div class=\"input-field col l9 s11\">{label}{input}</div>".
                    "<div class=\"col l3 s1 valign-wrapper helper\">{error}</div>",
//                'options' => ['class' => 'row'],
                'inputOptions' => ['autocomplete' => 'Off']
            ],
        ]); ?>
        <div class="card grey lighten-4">
            <div class="card-image no-img" style="background-image: url(<?= Yii::$app->request->baseUrl ?>assets/img/recruit/recruit-3.jpg)">
                <span class="recruit-text-f text-shadow">Вступление в компанию</span>
                <span class="recruit-text-s text-shadow">Шаг 3</span>
            </div>
            <div class="card-content row">
                <?php if(!$model->first_name) : ?>
                    <?= $form->field($model, 'first_name')->textInput()->label('Имя (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!$model->last_name) : ?>
                    <?= $form->field($model, 'last_name')->textInput()->label('Фамилия (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!$model->birth_date) : ?>
                    <?= $form->field($model, 'birth_date', ['template' => '<div class="col l9 s11">{label}{input}</div>'])
                        ->input('date', ['class' => 'datepicker-profile'])->label('Дата рождения (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!$model->country) : ?>
                    <?= $form->field($model, 'country')->textInput()->label('Страна (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!$model->city) : ?>
                    <?= $form->field($model, 'city')->textInput()->label('Город (обязательно)')->error(false) ?>
                <?php endif ?>


                <?php if(!$model->nickname) : ?>
                    <?= $form->field($model, 'nickname')->textInput()->label('Игровой никнейм (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!\app\models\RecruitForm::validateUrl('vk', $model->vk)) :
                    $model->vk = ''; ?>
                    <?= $form->field($model, 'vk')->textInput()->label('Ссылка на Ваш профиль ВКонтакте (обязательно)')->error(false) ?>
                <?php endif ?>

                <?php if(!\app\models\RecruitForm::validateUrl('steam', $model->steam)) :
                    $model->steam = ''; ?>
                    <?= $form->field($model, 'steam')->textInput()->label('Ссылка на Ваш профиль в Steam (обязательно)')->error(false) ?>
                <?php endif ?>

				<?= $form->field($model, 'hear_from', [
						'template' => '<div class="col l9 s11" style="margin-bottom: 20px">{label}{input}</div>',
						'options' => ['class' => 'col l6 s12']
				])->radioList([
						'Пригласил знакомый' => 'Пригласил знакомый',
						'Из интернета' => 'Из интернета',
						'Увидел на конвое' => 'Увидел на конвое',
						'Реклама в игровом чате' => 'Реклама в игровом чате',
						'Другой вариант' => 'Другой вариант (укажите в комментарие)'
					], ['item' => function($index, $label, $name, $checked, $value) {
						$return = '<p><input class="with-gap" type="radio" name="'.$name.'" value="'.$value.'" id="'.$value.'">';
						$return .= '<label for="'.$value.'">' . ucwords($label) . '</label></p>';
						return $return;
					},
						'tag' => false
					])->label('Как вы узнали про ВТК J.B. Hunt?') ?>

				<?= $form->field($model, 'dlc', [
					'template' => '<div class="col l9 s11" style="margin-bottom: 20px">{label}{input}</div>',
					'options' => ['class' => 'col l6 s12']
				])->checkboxList(\app\models\Convoys::getDLCList(), ['item' => function($index, $label, $name, $checked, $value) {
					$return = '<p><input type="checkbox" name="'.$name.'" value="'.$value.'" id="'.$value.'">';
					$return .= '<label for="'.$value.'">' . ucwords($label) . '</label></p>';
					return $return;
				},
					'tag' => false
				])->label('Какие DLC имеете?') ?>

				<?php if(Yii::$app->user->identity->has_ets || Yii::$app->user->identity->has_ats) : ?>
					<div class="col l9 s11">
						<?php if(Yii::$app->user->identity->has_ets) : ?>
							<?= $form->field($model, 'ets_playtime', [
								'template' => '<div class="input-field">{label}{input}</div>',
								'options' => ['class' => 'col l6 s12', 'style' => 'padding: 0 .75rem 0 0']
							])->input('number', ['min' => 0])->label('Ваше время в ETS2 (часов)') ?>
						<?php endif ?>

						<?php if(Yii::$app->user->identity->has_ats) : ?>
							<?= $form->field($model, 'ats_playtime', [
								'template' => '<div class="input-field">{label}{input}</div>',
								'options' => ['class' => 'col l6 s12', 'style' => 'padding: 0 0 0 .75rem']
							])->input('number', ['min' => 0])->label('Ваше время в ATS (часов)') ?>
						<?php endif ?>
					</div>
					<div class="clearfix"></div>
				<?php endif ?>

				<?= $form->field($model, 'invited_by', ['template' => '<div class="col l9 s11" style="margin-bottom: 20px">{label}{input}</div>'])
					->dropdownList($members, [
						'id' => 'members-select',
						'class' => 'browser-default'
					])?>

				<div class="col s12">
					<?= $form->field($model, 'companies', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Состоял ранее в ВТК') ?>

					<?= $form->field($model, 'mic', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Есть микрофон') ?>

					<?= $form->field($model, 'teamspeak', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Есть TeamSpeak') ?>
				</div>
				<div class="col s12">
					<?= $form->field($model, 'mods', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Пользуюсь модами для МП') ?>

					<?= $form->field($model, 'tedit', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Умею пользоваться Tedit') ?>

					<?= $form->field($model, 'save_editing', [
						'template' => '{input}{label}',
						'options' => [
							'tag' => false
						]
					])->checkbox(['label' => null])->error(false)->label('Умею пользоваться пропиской') ?>

				</div>

				<?= $form->field($model, 'comment')->textarea(['class' => 'materialize-textarea'])->label('Ваш комментарий к заявке (не обязательно)') ?>

            </div>
            <div class="card-action">
                <a href="<?= Url::to(['site/recruit', 'step' => '2']) ?>" class="btn indigo darken-3 waves-effect waves-light">
					<i class="material-icons notranslate left">arrow_back</i>Назад
				</a>
                <?=Html::submitButton('Подать заявку '.
                    Html::tag('i', 'send', ['class' => 'material-icons notranslate right']), [
                    'class' => 'btn indigo darken-3 waves-effect waves-light'
                ])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
		<script>
			$('#members-select').select2();
		</script>

    <?php endif ?>

</div>
<?php if($model->hasErrors()) : ?>
    <script>
        <?php foreach ($model->errors as $error): ?>
        Materialize.toast('<?= $error[0] ?>', 6000);
        <?php endforeach; ?>
    </script>
<?php endif ?>