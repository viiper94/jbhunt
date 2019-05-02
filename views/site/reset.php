<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Сброс пароля - J.B. Hunt'; ?>

<div class="container">
    <div class="card grey lighten-4">
        <div class="card-image no-img" style="background-image: url(<?= Yii::$app->request->baseUrl ?>/assets/img/reset_pwd.jpg)">
            <span class="card-title text-shadow">Сброс пароля</span>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-content row">
            <div class="input-field col l7 s12">
                <?= $form->field($model, 'password')->passwordInput()->error(false) ?>
            </div>
            <div class="input-field col l7 s12">
                <?= $form->field($model, 'password_2')->passwordInput()->error(false) ?>
            </div>
        </div>
        <div class="card-action">
            <?=Html::submitButton('Сохранить новый пароль' . Html::tag('i', 'send', [
                'class' => 'material-icons notranslate right'
            ]), ['class' => 'btn indigo darken-3 waves-effect waves-light']) ?>
        </div>
        <?php $form = ActiveForm::end(); ?>
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