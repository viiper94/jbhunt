<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подать заявление на отпуск - J.B. Hunt';
?>

<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col l12 s12">
            <div class="card grey lighten-4">
                <div class="card-content">
                    <span class="card-title">Укажите дату, до которой отпуск (мас. 3 месяца)</span>
                    <?= $form->field($model, 'to_date')->input('date', ['class' => 'datepicker-vacation'])->error(false) ?>
                </div>
                <div class="card-action">
                    <?=Html::submitButton('Подать заявление') ?>
                </div>
            </div>
            <div class="card-action"></div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php if($model->hasErrors()) : ?>
    <script>
        <?php foreach ($model->errors as $error): ?>
        Materialize.toast('<?= $error[0] ?>', 6000);
        <?php endforeach; ?>
    </script>
<?php endif ?>