<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$action = Yii::$app->controller->action->id == 'edit' ? 'Редактировать' : 'Добавить';
$this->title = $action . ' прицеп - Volvo Trucks';

?>

<div class="container row">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class=\"input-field col s12\">{label}{input}</div>",
            'options' => ['class' => 'row'],
        ]]) ?>
    <div class="col l6 m6 s12">
        <div class="card-panel grey lighten-4">
            <h5 class="light"><?= $action ?> трейлер</h5>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'description')->textarea(['class' => 'materialize-textarea']) ?>
            <?= $form->field($model, 'category')->dropDownList($categories)->label(false) ?>
            <?= $form->field($model, 'weight')->textInput() ?>
            <?= $form->field($model, 'game', ['template' => '{input}{label}'])
                ->radioList([
                    'ets' => 'Euro Truck Simulator 2',
                    'ats' => 'American Truck Simulator'
                ], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $return = '<p><input type="radio" name="' . $name . '" value="' . $value.'" id="'.$value.'"';
                        if($checked) $return .= ' checked';
                        $return .= '><label for="'.$value.'">'.$label.'</label></p>';
                        return $return;
                    }
                ])->label(false) ?>
        </div>
    </div>
    <div class="col l6 m6 s12">
        <div class="card-panel grey lighten-4">
            <?php if($model->picture == null) : ?>
                <img id="trailer-image" src="<?= Yii::$app->request->baseUrl ?>/images/trailers/default.jpg" class="responsive-img z-depth-3">
            <?php else: ?>
                <img id="trailer-image" src="<?= Yii::$app->request->baseUrl ?>/images/trailers/<?= $model->picture ?>" class="materialboxed responsive-img z-depth-3">
            <?php endif ?>
            <div class="file-field">
                <div class="btn indigo darken-3 waves-effect waves-light">
                    <span>Загрузить изображение</span>
                    <?= $form
						->field($model, 'picture', ['template' => '{input}'])
						->fileInput([
							'tag' => false,
							'class' => 'validate-img-size',
							'data-maxsize' => '15000000',
							'data-alert' => 'Максимальный размер файла 15Мб',
							'accept' => 'image/jpeg, image/png'
						])
						->label(false) ?>
                </div>
                <div class="file-path-wrapper input-field">
                    <input class="file-path" type="text" readonly="readonly" value="<?= $model->picture ?>">
					<span class="grey-text right">Максимальный размер файла 15Мб</span>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-action-btn vertical">
        <?=Html::submitButton(Html::tag('i', 'save', [
            'class' => 'large material-icons notranslate'
        ]), ['class' => 'btn-floating btn-large red']) ?>
        <ul>
            <li>
                <a onclick='return confirm("Удалить?")' href="<?=Url::to([
                    'trailers/remove',
                    'id' => $model->id
                ]) ?>" class="btn-floating yellow darken-3 tooltipped" data-position="left" data-tooltip="Удалить">
                    <i class="material-icons notranslate">delete</i>
                </a>
            </li>
        </ul>
    </div>
    <?php ActiveForm::end() ?>
</div>