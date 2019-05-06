<?php

use yii\helpers\Html;

$this->title = 'Редактирование вариаций - J.B. Hunt';
$this->registerJsFile(Yii::$app->request->baseUrl.'/lib/ck-editor/ckeditor.js?t='.time(),  ['position' => yii\web\View::POS_HEAD]); ?>

<div class="container" style="margin-bottom: 15px;">
    <div class="card grey lighten-4">
        <?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>
        <div class="card-image var-img">
            <img src="<?= Yii::$app->request->baseUrl ?>/images/variations/<?= $variation->image ?? 'default.jpg' ?>" class="responsive-img z-depth-2" id="preview">
            <div class="var-img">
                <label class="overlay valign-wrapper">
                    <i class="material-icons notranslate medium">file_upload</i>
                    <?= Html::fileInput('image', '', [
                        'class' => 'validate-img-size',
                        'data-maxsize' => '15000000',
                        'data-alert' => 'Максимальный размер файла 15Мб',
                        'style' => 'display: none',
                        'accept' => 'image/*'
                    ]) ?>
                </label>
            </div>
        </div>
        <div class="card-content">
            <?php if($action == 'add'): ?>
                <div class="row">
                    <p>
                        <?= Html::radio('game', false, ['required' => true, 'value' => 'ets2', 'id' => 'ets2', 'class' => 'with-gap']) ?>
                        <label for="ets2">ETS2</label>
                    </p>
                    <p>
                        <?= Html::radio('game', false, ['required' => true, 'value' => 'ats', 'id' => 'ats', 'class' => 'with-gap']) ?>
                        <label for="ats">ATS</label>
                    </p>
                </div>
            <?php else: ?>
                <?= Html::hiddenInput('game', $variation->game) ?>
            <?php endif ?>
            <div class="row">
                <div class="input-field">
                    <?= Html::textInput('name', $variation->name, ['id' => 'name', 'required' => true]) ?>
                    <label for="name">Название</label>
                </div>
            </div>
            <div class="row">
                <label for="description">Описание</label>
                <?= Html::textarea('description', $variation->description, ['id' => 'description']) ?>
            </div>
        </div>
        <div class="card-action">
            <?=Html::submitButton('Сохранить '.
                Html::tag('i', 'save', ['class' => 'material-icons notranslate right']), [
                'class' => 'btn indigo darken-3 waves-effect waves-light'
            ])?>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>

<script type="text/javascript">
    CKEDITOR.replace('description');
</script>