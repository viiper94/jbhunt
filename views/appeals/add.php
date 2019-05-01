<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Жалоба на сотрудника - Volvo Trucks';

$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/select2.min.js?t='.time(),  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2.min.css?t='.time());
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2-custom.css?t='.time());
?>

<div class="container">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col m6 s12">
            <div class="card-panel grey lighten-4">
                <h5 class="light">Жалоба на сотрудника</h5>
                <?= $form->field($model, 'appeal_to_id')->dropdownList($members, [
                    'id' => 'members-select',
                    'class' => 'browser-default',
                    'data-target' => 'trailers'
                ])->label(false) ?>
                <div class="input-field">
                    <?= $form->field($model, 'description')->textarea(['class' => 'materialize-textarea'])->error(false) ?>
                </div>
            </div>
        </div>
        <div class="col m6 s12">
            <div class="card-panel grey lighten-4">
                <p class="grey-text light">Жалобы без доказательств, скорее всего, не будут иметь никаких последствий</p>
                <div class="input-field">
                    <?= $form->field($model, 'proof')->textarea(['class' => 'materialize-textarea'])->error(false) ?>
                </div>
                <?php if(!Yii::$app->user->isGuest) : ?>
                    <?= $form->field($model, 'is_anonymous', ['template' => '{input}{label}'])
                        ->checkbox(['label' => null])->label('Анонимная жалоба') ?>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="fixed-action-btn">
        <?=Html::submitButton(Html::tag('i', 'save', [
            'class' => 'large material-icons notranslate'
        ]), ['class' => 'btn-floating btn-large red']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $('#members-select').select2();
</script>
<?php if($model->hasErrors()) : ?>
    <script>
        <?php foreach ($model->errors as $error): ?>
        Materialize.toast('<?= $error[0] ?>', 6000);
        <?php endforeach; ?>
    </script>
<?php endif ?>