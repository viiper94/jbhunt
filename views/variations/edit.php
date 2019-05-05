<?php

use yii\helpers\Html;

$this->title = 'Редактирование вариаций - J.B. Hunt';
$this->registerJsFile(Yii::$app->request->baseUrl.'/lib/ck-editor/ckeditor.js?t='.time(),  ['position' => yii\web\View::POS_HEAD]); ?>

<div class="container">
    <?= Html::beginForm(null, null, ['class' => 'variations-form']); ?>
        <h5>Вариации ETS2</h5>
        <div class="row">
            <div class="col l6 m12">

            </div>
            <div class="col l6 m12">
                <?= Html::textarea('ets2-var1', $rules->text, ['id' => 'ets2-var1']) ?>
                <?= Html::textarea('ets2-var1', $rules->text, ['id' => 'ets2-var1']) ?>
            </div>
        </div>
    <?= Html::endForm(); ?>
</div>

<script type="text/javascript">
    CKEDITOR.replace('ets2-var1');
</script>