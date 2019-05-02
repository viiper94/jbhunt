<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Правила ВТК J.B. Hunt';

?>

<div class="container">
    <?php if(!$edit) : ?>
    <div class="card grey lighten-4">
        <div class="card-image no-img" style="background-image: url(assets/img/rules.jpg)">
            <span class="card-title text-shadow"><?= $this->title ?></span>
        </div>
        <div class="card-content list-style-default">
            <?= $rules->text ?>
			<p class="grey-text">Последнее изменение правил: <?= Yii::$app->formatter->asDate($rules->date, 'long') ?></p>
        </div>
    </div>
    <?php if(\app\models\User::isAdmin()): ?>
        <div class="fixed-action-btn">
            <a href="<?= Url::to(['site/rules', 'action' => 'edit']) ?>" class="btn-floating btn-large red"><i class="large material-icons notranslate">edit</i></a>
        </div>
    <?php endif ?>
    <?php else:
        $this->registerJsFile(Yii::$app->request->baseUrl.'/lib/ck-editor/ckeditor.js?t='.time(),  ['position' => yii\web\View::POS_HEAD]); ?>
        <?= Html::beginForm(null, null, ['class' => 'rules-form']); ?>
        <?= Html::textarea('rules', $rules->text, [
                'id' => 'rules'
        ]) ?>
        <?= Html::checkbox('notify', false, ['id' => 'notify']) ?><label for="notify" style="margin-top: 10px">Уведомить всех сотрудников об изменении правил</label>
        <div class="fixed-action-btn">
            <?=Html::submitButton(Html::tag('i', 'save', [
                'class' => 'large material-icons notranslate'
            ]), ['class' => 'btn-floating btn-large red']) ?>
        </div>
        <?= Html::endForm(); ?>
        <script type="text/javascript">
            CKEDITOR.replace('rules');
        </script>
    <?php endif ?>
</div>
<?php if($errors) : ?>
    <script>
        Materialize.toast('<?= $errors[0] ?>', 6000);
    </script>
<?php endif ?>