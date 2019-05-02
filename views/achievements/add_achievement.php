<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::$app->controller->action->id == 'edit' ? 'Редактировать' : 'Создать';
$this->title .= ' достижение - J.B. Hunt';
?>

<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin() ?>
        <div class="col s12 m6">
            <div class="card-panel grey lighten-4">
                <h5 class="light">Основная информация</h5>
                <div class="input-field">
                    <?= $form->field($model, 'title')->textInput()->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'description')->textarea(['class' => 'materialize-textarea']) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'progress')->input('number', ['min' => 1])->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'scores')->input('number', ['min' => 0])->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'related', ['template' => '{input}{label}'])
                        ->dropdownList(\yii\helpers\ArrayHelper::merge(['' => 'Без зависимости'], $related))
                        ->error(false) ?>
                </div>
                <?= $form->field($model, 'visible', ['template' => '{input}{label}'])->checkbox(['label' => null])->label('Сделать достижение видимым') ?>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card-panel grey lighten-4">
                <img src="<?= Yii::$app->request->baseUrl ?>/images/achievements/<?= $model->image ? $model->image : 'default.jpg' ?>" class="responsive-img z-depth-2" id="preview" style="width: 100%;">
                <div class="input-field file-field">
                    <div class="btn indigo darken-3 waves-effect waves-light">
                        <span>Изображение</span>
                        <?= $form->field($model, 'image')->fileInput([
							'accept' => 'image/jpeg, image/png',
							'class' => 'validate-img-size',
							'data-maxsize' => '15000000',
							'data-alert' => 'Максимальный размер файла 15Мб',
						])->label(false)->error(false) ?>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text" readonly="readonly" value="<?= $model->image ?>">
                    </div>
					<span class="grey-text right">Максимальный размер файла 15Мб</span>
					<div class="clearfix"></div>
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
</div>
<?php if($model->hasErrors()) : ?>
    <script>
        <?php foreach ($model->errors as $error): ?>
        Materialize.toast('<?= $error[0] ?>', 6000);
        <?php endforeach; ?>
    </script>
<?php endif ?>