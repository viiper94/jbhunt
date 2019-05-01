<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактировать заявление на смену никнейма - Volvo Trucks';
?>

<div class="container">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <h5 class="col l6 s12 light">Заявление на смену никнейма</h5>
		<?php if($model->viewed) : ?>
			<h6 class="col l6 s12 viewed-by">Рассмотрел:
				<a href="<?= Url::to(['site/profile', 'id' => $model->viewed]) ?>" style="color: inherit">
					<?= $model->claim->a_first_name ?> <?= $model->claim->a_last_name ?>
				</a>
			</h6>
		<?php endif ?>
    </div>
    <?php if(\app\models\User::isAdmin()) : ?>
        <div class="card-panel grey lighten-4 user">
            <div class="link-image">
                <a href="<?= Url::to(['site/profile', 'id' => $model->claim->id]) ?>"
				   class="circle z-depth-3 waves-effect waves-light <?php if(\app\models\User::isOnline($model->claim)) : ?>online<?php endif ?>"
				   style="background-image: url(<?= Yii::$app->request->baseUrl ?>/web/images/users/<?= $model->claim->picture ?>)">
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
        <div class="card-content">
            <div class="input-field">
				<?= $form->field($model, 'new_nickname')->textInput(['readonly' =>(Yii::$app->user->id == $model->user_id ? false : true)]) ?>
            </div>
            <?php if(\app\models\User::isAdmin()) : ?>
                <div class="row">
                    <div class="input-field col l5 s12">
                        <?= $form->field($model, 'status')->dropdownList([
                            '0' => 'Рассматривается',
                            '1' => 'Одобрено',
                            '2' => 'Отказ'
                        ])->label(false)->error(false) ?>
                    </div>
                    <div class="input-field col l7 s12">
                        <?= $form->field($model, 'reason')->textInput() ?>
                    </div>
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
    <div class="fixed-action-btn">
        <?=Html::submitButton(Html::tag('i', 'save', [
                'class' => 'large material-icons notranslate'
        ]), ['class' => 'btn-floating btn-large red']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>