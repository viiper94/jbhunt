<?php

use yii\helpers\Url;
use app\models\User; ?>

<div id="nickname">

    <div class="valign-wrapper" style="justify-content: space-between">
        <h5 class="light">Заявления на смену ника</h5>
        <?php if(User::isVtcMember()) : ?>
            <a href="<?= Url::to(['claims/add', 'claim' => 'nickname']) ?>" class="btn indigo waves-effect waves-light">
                Подать заявление на смену ника<i class="material-icons notranslate right">add_circle</i>
            </a>
        <?php endif ?>
    </div>

    <?php foreach($nickname as $claim) :
        switch ($claim->status){
            case '1': $color_class = 'light-green lighten-4-5'; break;
            case '2': $color_class = 'red lighten-4-5'; break;
            case '0':
            default : $color_class = 'grey lighten-4'; break;
        } ?>
        <div class="card horizontal hoverable <?= $color_class ?>">
            <div class="card-image grey lighten-4 no-img_horizontal" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/<?= $claim->picture ?>)">
                <a href="<?= Url::to(['site/profile', 'id' => $claim->user_id]) ?>" class="waves-effect waves-light"></a>
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <a class="card-title black-text" href="<?= Url::to(
                    		$claim->member_id ?
							['members/edit', 'id' => $claim->member_id] :
							['site/profile', 'id' => $claim->user_id]
					) ?>">
                        <span class="old-nickname">[Volvo Trucks] <?= htmlentities($claim->old_nickname) ?></span>
                        &rArr;
                        <span class="new-nickname">[Volvo Trucks] <?= htmlentities($claim->new_nickname) ?></span>
                    </a>
                    <div class="flex claim-info">
                        <div style="max-width: 70%">
                            <p class="nowrap"><?= Yii::$app->formatter->asDate($claim->date, 'long') ?></p>
                        </div>
                        <div class="claim-status" style="flex: 1;">
                            <p class="fs17 bold"><?= $claim->getStatusTitle() ?></p>
                            <?php if($claim->viewed): ?>
                                <a class="grey-text" href="<?= Url::to(['site/profile', 'id' =>$claim->viewed]) ?>">
									Рассмотрел: <?= $claim->a_first_name ?> <?= $claim->a_last_name ?>
								</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->id == $claim->user_id ||
                        Yii::$app->user->identity->admin == 1) && $claim->status == 0 || User::isAdmin()) : ?>
                    <div class="card-action">
                        <?php if(User::isAdmin() && $claim->status == 0) : ?>
                            <a onclick='return confirm("Одобрить заявку?")' href="<?= Url::to(['claims/apply',
                                'claim' => 'nickname',
                                'id' => $claim->id
                            ]) ?>"><i class="material-icons notranslate to-text">done</i>Одобрить
                            </a>
                        <?php endif; ?>
                        <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->id == $claim->user_id ||
                                Yii::$app->user->identity->admin == 1) && $claim->status == 0) : ?>
                            <a href="<?= Url::to(['claims/edit',
                                'claim' => 'nickname',
                                'id' => $claim->id
                            ]) ?>"><i class="material-icons notranslate to-text">edit</i>Редактировать
                            </a>
                        <?php endif; ?>
                        <?php if(User::isAdmin()) : ?>
                            <a onclick='return confirm("Удалить?")' href="<?=Url::to(['claims/remove',
                                'claim' => 'nickname',
                                'id' => $claim->id
                            ]) ?>"><i class="material-icons notranslate to-text">delete</i>Удалить
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>

</div>
