<?php

use app\models\VtcMembers;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/select2.min.js?t='.time(),  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2.min.css?t='.time());
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2-custom.css?t='.time());
$this->title = 'Редактировать заявление на вступление - J.B. Hunt';
?>

<div class="container">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <h5 class="col l6 s12 light">Заявление на вступление</h5>
        <?php if($model->viewed) : ?>
            <h6 class="col l6 s12 viewed-by">Рассмотрел:
				<a style="color: inherit" href="<?= Url::to(['site/profile', 'id' =>$model->viewed]) ?>">
					<?= $model->claim->a_first_name ?> <?= $model->claim->a_last_name ?>
				</a>
			</h6>
        <?php endif ?>
    </div>
    <?php if(\app\models\User::isAdmin()) : ?>
        <div class="card-panel grey lighten-4 user">
            <div class="link-image">
                <a href="<?= Url::to(['site/profile', 'id' => $model->claim->user_id]) ?>"
				   class="circle z-depth-3 waves-effect waves-light <?php if(\app\models\User::isOnline($model->claim)) : ?>online<?php endif ?>"
				   style="background-image: url(<?= Yii::$app->request->baseUrl ?>/images/users/<?= $model->claim->picture ?>)">
                </a>
            </div>
            <div class="user-info row">
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span><b><?= $model->claim->company != '' ? '['.$model->claim->company.']' : '' ?></b></span></div>
                    <div class="col l7 s7 profile-info"><span><b><?=$model->claim->nickname?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span>Имя:</span></div>
                    <div class="col l7 s7 profile-info"><span><b><?=$model->claim->first_name?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span>Фамилия:</span></div>
                    <div class="col l7 s7 profile-info"><span><b><?=$model->claim->last_name?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align truncate"><span>Дата рождения:</span></div>
                    <div class="col l7 s7 profile-info truncate"><span><b><?= Yii::$app->formatter->asDate($model->claim->birth_date, 'long') ?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span>Страна:</span></div>
                    <div class="col l7 s7 profile-info"><span><b><?=$model->claim->country?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span>Город:</span></div>
                    <div class="col l7 s7 profile-info"><span><b><?=$model->claim->city?></b></span></div>
                </div>
                <div class="col l12 s12">
                    <div class="col l5 s5 right-align"><span>Зарегестрирован:</span></div>
                    <div class="col l7 s7 profile-info"><span><b><?= Yii::$app->formatter->asDate($model->claim->registered, 'long') ?></b></span></div>
                </div>
            </div>
            <div class="user-links">
                <ul class="socials links">
                    <?php if($model->claim->vk){ ?>
                        <li class="vk"><a class="waves-effect circle" target="_blank" href="<?=$model->claim->vk?>"></a></li>
                    <?php }
                    if($model->claim->steam){ ?>
                        <li class="steam"><a class="waves-effect circle" target="_blank" href="<?=$model->claim->steam?>"></a></li>
                    <?php }
                    if($model->claim->truckersmp){ ?>
                        <li class="truckers-mp"><a class="waves-effect circle" target="_blank" href="<?=$model->claim->truckersmp?>"></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php endif ?>
    <div class="card grey lighten-4">
        <div class="card-content row">
            <?php if(Yii::$app->user->id == $model->claim->user_id){ ?>
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
					$return = '<p><input class="with-gap" type="radio" name="'.$name.'" value="'.$value.'" id="'.$value.'"'.($checked ? ' checked' : '').'>';
					$return .= '<label for="'.$value.'">' . ucwords($label) . '</label></p>';
					return $return;
				},
					'tag' => false
				])->label('Как вы узнали про ВТК J.B. Hunt?') ?>

				<?= $form->field($model, 'dlc', [
					'template' => '<div class="col l9 s11" style="margin-bottom: 20px">{label}{input}</div>',
					'options' => ['class' => 'col l6 s12']
				])->checkboxList(\app\models\Convoys::getDLCList(), ['item' => function($index, $label, $name, $checked, $value) {
					$return = '<p><input type="checkbox" name="'.$name.'" value="'.$value.'" id="'.$value.'"'.($checked ? ' checked' : '').'>';
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
					->dropdownList(array_replace(['' => 'Никто не приглашал / Другой человек'], VtcMembers::getMembersArray()), [
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
                <div class="input-field col l9">
                    <?= $form->field($model, 'comment')->textarea(['class' => 'materialize-textarea']) ?>
                </div>
				<script>
					$('#members-select').select2();
				</script>
            <?php }else{ ?>
				<div class="col m6 s12">
					<p>Как узнали про ВТК J.B. Hunt: <b><?= $model->claim->hear_from ? $model->claim->hear_from : '&mdash;' ?></b></p>
					<?= $form->field($model, 'hear_from')->hiddenInput()->label(false) ?>
					<p>Кто пригласил:
						<b>
							<a href="<?= Url::to(['site/profile', 'id' => $model->claim->i_id]) ?>" style="color: inherit;">
								<?= $model->claim->invited_by ? ($model->claim->i_company ? '['.$model->claim->i_company.'] ' : ' ').$model->claim->i_nickname : '&mdash;' ?>
								<?= $form->field($model, 'invited_by')->hiddenInput()->label(false) ?>
							</a>
						</b>
					</p>
					<ul>
						<li class="<?= $model->claim->companies ? 'bold' : 'grey-text' ?>">Состоял ранее в ВТК</li>
						<li class="<?= $model->claim->mic ? 'bold' : 'grey-text' ?>">Есть микрофон</li>
						<li class="<?= $model->claim->teamspeak ? 'bold' : 'grey-text' ?>">Есть TeamSpeak</li>
						<li class="<?= $model->claim->mods ? 'bold' : 'grey-text' ?>">Пользуюсь модами для МП</li>
						<li class="<?= $model->claim->tedit ? 'bold' : 'grey-text' ?>">Умею пользоваться Tedit</li>
						<li class="<?= $model->claim->save_editing ? 'bold' : 'grey-text' ?>">Умею пользоваться пропиской</li>
					</ul>
					<?= $form->field($model, 'companies')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'mic')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'teamspeak')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'mods')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'tedit')->hiddenInput()->label(false) ?>
					<?= $form->field($model, 'save_editing')->hiddenInput()->label(false) ?>
					<p>Коментарий к заявке:
						<b>
							<?= $model->claim->comment ? $model->claim->comment : '&mdash;' ?>
							<?= $form->field($model, 'comment')->hiddenInput()->label(false) ?>
						</b>
					</p>
					<?php if($model->claim->ets_playtime) : ?>
					    <p>Опыт в ETS2: <b><?= $model->claim->ets_playtime ?> ч.</b></p>
					<?php endif ?>
					<?php if($model->claim->ats_playtime) : ?>
					    <p>Опыт в ATS: <b><?= $model->claim->ats_playtime ?> ч.</b></p>
					<?php endif ?>
				</div>
				<div class="col m6 s12">
					<h6>Наличие DLC:</h6>
					<ul>
						<?php foreach(\app\models\Convoys::getDLCList() as $name) : ?>
							<li class="<?= in_array($name, explode('%', $model->claim->dlc)) ? 'bold' : 'grey-text' ?>"><?= $name ?></li>
						<?php endforeach ?>
					</ul>
				</div>
            <?php } ?>
			<div class="clearfix"></div>
            <?php if(\app\models\User::isAdmin()) : ?>
				<div class="input-field col l3 s12">
					<?= $form->field($model, 'status')->dropdownList([
						'0' => 'Рассматривается',
						'1' => 'Одобрено',
						'2' => 'Отказ',
						'3' => 'На удержании'
					])->label(false)->error(false) ?>
				</div>
				<div class="col l6 s12" style="display: none;" id="claim-reasons">
					<?= $form->field($model, 'reason', [
						'template' => '{label}{input}',
						'options' => ['class' => '']
					])->checkboxList($model->claim->getReasonList(), ['item' => function($index, $label, $name, $checked, $value) {
						$return = '<p><input type="checkbox" name="'.$name.'" value="'.$value.'" id="'.$value.'"'.($checked ? ' checked' : '').'>';
						$return .= '<label for="'.$value.'">' . ucwords($label) . '</label></p>';
						return $return;
					},
						'tag' => false
					])->label('Причина отказа') ?>
					<?= $form->field($model, 'reason[text]', [
							'template' => '<div class="input-field col l8 s12">{label}{input}</div>'
					])->textInput()->label('Кастомный текст')->error(false) ?>
				</div>
            <?php endif ?>
        </div>
        <div class="card-action">
            <?=Html::submitButton('Сохранить') ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php if(\app\models\User::isAdmin()) : ?>
        <?= $form->field($model, 'viewed')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
    <?php endif ?>
    <?php ActiveForm::end(); ?>
</div>