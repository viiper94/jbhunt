<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Выставить баллы за конвой';
?>

<div class="container">
    <?php if($convoy->scores_set == '1') : ?>
        <h5 class="light">Выставление баллов за этот конвой уже не требуется.</h5>
    <?php else: ?>
        <h5 class="light">
            Выставление баллов за
            <a href="<?= Url::to(['convoys/index', 'id' => $convoy->id]) ?>"> "<?= $convoy->title ?>" от <?= \app\controllers\SiteController::getRuDate($convoy->date) ?></a>
        </h5>
        <form action="<?= Url::to(['convoys/scores', 'id' => $convoy->id]) ?>" method="post">
            <?= Html :: hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []) ?>
            <div class="card-panel grey lighten-4">
                <div class="row">
                    <h6 class="light fs17 col m6 s12">Отметьте учасников конвоя</h6>
                    <div class="switch col m6 s12 right-align">
                        <label class="fs17 light">
                            В другое
                            <input type="checkbox" name="month" checked>
                            <span class="lever"></span>
                            В месяц
                        </label>
                    </div>
                </div>
                <table class="highlight">
                    <thead>
                    <tr>
                        <th>Никнейм</th>
                        <th class="center">0 баллов</th>
                        <th class="center">5 баллов</th>
                        <th class="center">10 баллов</th>
                        <th class="center">20 баллов</th>
                        <th class="center">Ведущий</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0;
                    foreach($all_members as $post => $members) :
                        if($post != 'Администрация'): ?>
                            <tr>
                                <th class="center"><?= $post ?></th>
                                <?php if($i > 1) : ?>
                                    <th class="center">0 баллов</th>
                                    <th class="center">5 баллов</th>
                                    <th class="center">10 баллов</th>
                                    <th class="center">20 баллов</th>
                                    <th class="center">Ведущий</th>
                                <?php endif ?>
                            </tr>
                            <?php foreach($members as $member) : ?>
                                <tr>
                                    <td>
                                        <a href="<?= Url::to(['members/edit', 'id' => $member->id]) ?>" class="black-text">
                                            [Volvo Trucks] <?= $member->nickname ?>
                                        </a>
                                    </td>
                                    <td class="center">
                                        <input name="scores[<?= $member->id ?>]" type="radio" id="<?= $member->id ?>_0" value="0" class="with-gap">
                                        <label for="<?= $member->id ?>_0"></label>
                                    </td>
                                    <td class="center">
                                        <input name="scores[<?= $member->id ?>]" type="radio" id="<?= $member->id ?>_5" value="5" class="with-gap">
                                        <label for="<?= $member->id ?>_5"></label>
                                    </td>
                                    <td class="center">
                                        <input name="scores[<?= $member->id ?>]" type="radio" id="<?= $member->id ?>_10" value="10" class="with-gap">
                                        <label for="<?= $member->id ?>_10"></label>
                                    </td>
                                    <td class="center">
                                        <input name="scores[<?= $member->id ?>]" type="radio" id="<?= $member->id ?>_20" value="20" class="with-gap">
                                        <label for="<?= $member->id ?>_20"></label>
                                    </td>
                                    <td class="center">
                                        <input name="lead" type="radio" id="lead_<?= $member->id ?>" value="<?= $member->id ?>">
                                        <label for="lead_<?= $member->id ?>"></label>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif;
                        $i++; ?>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <div class="fixed-action-btn">
                    <?=Html::submitButton(Html::tag('i', 'save', [
                        'class' => 'large material-icons notranslate'
                    ]), ['class' => 'btn-floating btn-large red']) ?>
                </div>
            </div>
        </form>
    <?php endif ?>
</div>