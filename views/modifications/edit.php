<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = (Yii::$app->controller->action->id == 'add' ? 'Добавить' : 'Редактировать') .' модификацию - J.B. Hunt';
$this->registerJsFile(Yii::$app->request->baseUrl.'/assets/js/select2.min.js?t='.time(),  ['position' => yii\web\View::POS_HEAD, 'depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2.min.css?t='.time());
$this->registerCssFile(Yii::$app->request->baseUrl.'/assets/css/select2-custom.css?t='.time());
?>

<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
        <h5 class="light col l12 s12"><?= Yii::$app->controller->action->id == 'add' ? 'Добавление' : 'Редактирование' ?> модификации</h5>
        <div class="col l6 s12">
            <div class="card-panel grey lighten-4">
                <h5 class="light">Изображение/прицеп</h5>
                <div class="row">
                    <div class="col l11 s10">
                        <?= $form->field($model, 'trailer')
                            ->dropdownList($trailers, [
                                'id' => 'trailer-select',
                                'class' => 'browser-default trailers-select',
                                'data-target' => 'mods'])
                            ->error(false)
                            ->label(false) ?>
                    </div>
                    <div class="col l1 s2 center" style="line-height: 66px;">
                        <a href="<?= Url::to(['site/trailers', 'action' => 'add']) ?>" class="tooltipped indigo-text" data-position="bottom" data-tooltip="Добавить новый трейлер">
                            <i class="material-icons notranslate small">add</i>
                        </a>
                    </div>
                </div>
                <div class="input-field file-field">
                    <div class="btn indigo darken-3 waves-effect waves-light">
                        <span>Изображение</span>
                        <?= $form->field($model, 'picture')->fileInput([
							'class' => 'validate-img-size',
							'data-maxsize' => '15000000',
							'data-alert' => 'Максимальный размер файла 15Мб',
							'accept' => 'image/jpeg, image/png'
						])->label(false)->error(false) ?>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text" placeholder="Выбрать, если не прицеп" readonly="readonly" value="<?= $model->picture ?>">
                    </div>
					<span class="grey-text right">Максимальный размер файла 15Мб</span>
					<div class="clearfix"></div>
                </div>

                <div class="center">
                    <?= $form->field($model, 'dlc[Scandinavia]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Scandinavia') ?>
                    <?= $form->field($model, 'dlc[Vive La France!]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Vive La France!') ?>
                    <?= $form->field($model, 'dlc[Italia]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Italia') ?>
                    <?= $form->field($model, 'dlc[Schwarzmüller Trailer Pack]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Schwarzmüller Trailer Pack') ?>
                    <?= $form->field($model, 'dlc[Heavy Cargo Pack]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Heavy Cargo Pack') ?>
                    <?= $form->field($model, 'dlc[High Power Cargo Pack]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('High Power Cargo Pack') ?>
                    <?= $form->field($model, 'dlc[Special Transport]', [
                        'template' => '{input}{label}',
                        'options' => [
                            'tag' => false
                        ]
                    ])->checkbox(['label' => null])->error(false)->label('Special Transport') ?>
                </div>
            </div>
        </div>
        <div class="col l6 s12">
            <div class="card-panel grey lighten-4">
                <div id="trailer-info">
                    <h6 class="light" id="trailer-name" style="font-weight: bold;"><?= $model->tr_name ?></h6>
                    <img src="<?= Yii::$app->request->baseUrl . '/images/' . $model->picture ?>"
                         class="responsive-img z-depth-2" id="trailer-image" style="width: 100%;">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col l6 s12">
            <div class="card-panel grey lighten-4">
                <h5 class="light">Основная информация</h5>
                <?= $form->field($model, 'category')->dropDownList($categories)->error(false) ?>
                <div class="input-field">
                    <?= $form->field($model, 'title')->textInput()->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'description')->textarea(['class' => 'materialize-textarea'])->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'warning')->textarea(['class' => 'materialize-textarea'])->error(false) ?>
                </div>
            </div>
        </div>
        <div class="col l6 s12">
            <div class="card-panel grey lighten-4">
                <h5 class="light">Ссылки на скачивание</h5>
                <label>Файл модификации (макс. 2 Мб)</label>
                <div class="file-field">
                    <div class="btn indigo darken-3 waves-effect waves-light">
                        <span>Файл</span>
                        <?= $form->field($model, 'file')->fileInput([
							'class' => 'validate-img-size',
							'data-maxsize' => '2000000',
							'data-alert' => 'Максимальный размер файла 2Мб',
							'accept' => '.scs'
						])->label(false) ?>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path" type="text" value="<?= $model->file_name ?>" readonly="readonly">
                    </div>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'yandex_link')->textInput()->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'gdrive_link')->textInput()->error(false) ?>
                </div>
                <div class="input-field">
                    <?= $form->field($model, 'mega_link')->textInput()->error(false) ?>
                </div>
            </div>
        </div>
        <div class="fixed-action-btn">
            <?=Html::submitButton(Html::tag('i', 'save', [
                'class' => 'large material-icons notranslate'
            ]), ['class' => 'btn-floating btn-large red waves-effect waves-light']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    $('#trailer-select').select2();
</script>