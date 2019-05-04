<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование профиля - Volvo Trucks';

$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/jquery.ui.widget.js',  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/jquery.iframe-transport.js',  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/jquery.fileupload.js',  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="container">
    <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'fieldConfig' => [
                'template' => "<div class=\"input-field col s11\">{label}{input}</div>",
                'options' => ['class' => 'row'],
                'inputOptions' => ['autocomplete' => 'Off']
            ],
            'options' => ['enctype'=>'multipart/form-data']
        ]);?>
    <div class="card grey lighten-4">
        <div class="card-image no-img" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/bg/<?= $user->bg_image ?>)">
            <div class="bg-img">
                <label class="overlay valign-wrapper">
                    <i class="material-icons notranslate medium">file_upload</i>
                    <?= $form->field($model, 'bg_image', [
                        'template' => '{input}{label}',
                        'options' => ['tag' => false]
                    ])->fileInput([
						'class' => 'validate-img-size',
						'data-maxsize' => '15000000',
						'data-alert' => 'Максимальный размер файла 15Мб',
                        'style' => 'display: none',
                        'accept' => 'image/*'
                    ])->label(false) ?>
                </label>
            </div>
        </div>
        <div class="card-content">
            <div class="profile-img z-depth-3" id="preview" style="background-image: url(<?=Yii::$app->request->baseUrl.'/images/users/'.$user->picture.'?t='.time()?>)">
                <label class="overlay valign-wrapper">
                    <i class="material-icons notranslate white-text">file_upload</i>
                    <?= $form->field($model, 'picture', [
                        'template' => '{input}{label}',
                        'options' => ['tag' => false]
                    ])->fileInput([
                        'style' => 'display: none',
                        'accept' => 'image/*'
                    ])->label(false) ?>
                </label>
            </div>
            <div class="row" style="margin-top: 50px;">
                <div class="col m6 s12">
                    <span class="card-title">Редактирование профиля</span>
                    <?= $form->field($model, 'first_name')->textInput([
                        'value' => $user->first_name,
                        'readonly' => $member ? 'true' : false
                    ])->label('Имя') ?>
                    <?= $form->field($model, 'last_name')->textInput([
                        'value' => $user->last_name,
                        'readonly' => $member ? 'true' : false
                    ])->label('Фамилия') ?>
                    <?= $form->field($model, 'username')->textInput([
                        'required' => 'required',
                        'value' => Yii::$app->user->identity->username
                    ])->label('Логин*')->error(false) ?>
                    <?= $form->field($model, 'email')->input('email', [
                        'required' => 'required',
                        'value' => $user->email
                    ])->label('E-Mail*')->error(false) ?>
                    <?= $form->field($model, 'country')->textInput([
                        'value' => $user->country
                    ])->label('Страна') ?>
                    <?= $form->field($model, 'city')->textInput([
                        'value' => $user->city
                    ])->label('Город') ?>
                </div>
                <div class="col m6 s12">
                    <?php if($member) : ?>
                        <div class="row">
                            <div class="input-field col s11">
                                <label>Дата рождения</label>
                                <input type="text" readonly value="<?= \app\controllers\SiteController::getRuDate($user->birth_date) ?>">
                            </div>
                        </div>
                        <?= $form->field($model, 'birth_date')
                            ->hiddenInput(['readonly' => true, 'value' => $user->birth_date])
                            ->label(false) ?>
                    <?php else: ?>
                        <div class="col s11">
                            <?= $form->field($model, 'birth_date', ['template' => '{label}{input}'])->input('date', [
                                'class' => 'datepicker-profile',
                                'data-value' => $user->birth_date,
                                'readonly' => $member ? 'true' : false
                            ])->label('Дата рождения') ?>
                        </div>
                    <?php endif ?>
                    <?= $form->field($model, 'vk')->textInput([
                        'value' => $user->vk,
                        'readonly' => $member ? 'true' : false
                    ])->label('Профиль VK') ?>
                    <?= $form->field($model, 'steam')->textInput([
                        'value' => $user->steam,
                        'readonly' => $member || $user->social == 'steam' ? 'true' : false
                    ])->label('Профиль Steam') ?>
                    <?= $form->field($model, 'steamid64')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'visible_steam', ['template' => '<div>{input}{label}</div>'])
                        ->checkbox(['label' => null])->error(false)->label('Сделать профиль Steam видимым для всех') ?>
                    <?= $form->field($model, 'visible_truckersmp', ['template' => '<div>{input}{label}</div>'])
                        ->checkbox([
                            'label' => null,
                            'disabled' => $user->steam == '' ? 'true' : false
                        ])->error(false)->label('Показать профиль TruckersMP') ?>
                    <?php $display = $user->visible_truckersmp == '1' ? 'block' : 'none' ?>
                    <?= $form->field($model, 'truckersmp')->textInput([
                        'value' => $user->truckersmp,
                        'style' => 'display: '.$display,
                        'readonly' => 'true'
                    ])->label(false) ?>
                    <?= $form->field($model, 'nickname')->textInput([
                        'value' => $user->nickname,
                        'readonly' => $member ? 'true' : false
                    ])->label('Ваш игровой псевдоним') ?>
                    <?= $form->field($model, 'has_ets', ['template' => '<div>{input}{label}</div>'])
                        ->checkbox(['label' => null])->error(false)->label('Есть <b>Euro Truck Simulator 2</b>') ?>
                    <?= $form->field($model, 'has_ats', ['template' => '<div>{input}{label}</div>'])
                        ->checkbox(['label' => null])->error(false)->label('Есть <b>American Truck Simulator</b>') ?>
                </div>
            </div>
            <?php if($member): ?>
                <div class="col s12">
                    <p class="grey-text">*Сотрудники ВТК ограничены в редактировании некоторых данных профиля. Для их изменения, пожалуйста, обратитесь к одному из администраторов.</p>
                </div>
            <?php endif ?>
        </div>
        <div class="card-action">
            <?=Html::submitButton('Сохранить '.
                Html::tag('i', 'save', ['class' => 'material-icons notranslate right']), [
                'class' => 'btn indigo darken-3 waves-effect waves-light',
                'name' => 'save_profile'
            ]);
            ActiveForm::end(); ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'password-form',
        'fieldConfig' => [
            'template' => "<div class=\"input-field col s9\">{label}{input}</div>".
                "<div class=\"col l3 valign-wrapper helper\">{error}</div>",
            'options' => ['class' => 'row'],
            'inputOptions' => ['autocomplete' => 'Off']
        ]
    ]);?>
    <div class="card grey lighten-4">
        <div class="card-content">
            <span class="card-title">Изменить пароль</span>
			<?php if($pass_set): ?>
				<?= $form->field($pass_model, 'password')->passwordInput()->label('Старый пароль*') ?>
			<?php else : ?>
				<?= $form->field($pass_model, 'password', ['template' => '{input}'])->hiddenInput(['value' => 'false'])->label(false) ?>
			<?php endif ?>
            <?= $form->field($pass_model, 'password_new')->passwordInput()->label('Новый пароль*') ?>
            <?= $form->field($pass_model, 'password_new_2')->passwordInput()->label('Повторите новый пароль*') ?>
        </div>
        <div class="card-action">
            <?=Html::submitButton('Изменить пароль '.
                Html::tag('i', 'save', ['class' => 'material-icons notranslate right']), [
                'class' => 'btn indigo darken-3 waves-effect waves-light',
                'name' => 'save_profile_password'
            ])?>
        </div>
    </div>
    <?php if($model->hasErrors()) : ?>
        <script>
            <?php foreach ($model->errors as $error): ?>
            Materialize.toast('<?= $error[0] ?>', 6000);
            <?php endforeach; ?>
        </script>
    <?php endif ?>
    <?php ActiveForm::end(); ?>
</div>