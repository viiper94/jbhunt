<?php

use yii\helpers\Url;
use app\models\User; ?>

<div id="vacation">

    <div class="valign-wrapper" style="justify-content: space-between">
        <h5 class="light">Заявления на отпуск</h5>
        <?php if(User::isVtcMember()) : ?>
            <a href="<?= Url::to(['claims/add', 'claim' => 'vacation']) ?>" class="btn indigo waves-effect waves-light">
                Подать заявление на отпуск<i class="material-icons notranslate right">add_circle</i>
            </a>
        <?php endif ?>
    </div>

    <?php foreach($vacation as $claim) :
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
						$claim->v_member_id ?
							['members/edit', 'id' => $claim->v_member_id] :
							['site/profile', 'id' => $claim->user_id]
					) ?>">
						[J.B. Hunt] <?= htmlentities($claim->nickname) ?>
					</a>
                    <div class="flex claim-info">
                        <div style="max-width: 70%">
                            <p class="nowrap">С <?= Yii::$app->formatter->asDate($claim->date, 'long') ?></p>
                            <p class="nowrap"><b><?= $claim->vacation_undefined == '1' ?
										'На Н. срок' :
										'По ' . Yii::$app->formatter->asDate($claim->to_date, 'long') ?></b></p>
                        </div>
                        <div class="claim-status" style="flex: 1;">
                            <p class="fs17 bold"><?= $claim->getStatusTitle() ?><br><?= strip_tags($claim->reason) ?></p>
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
                                'claim' => 'vacation',
                                'id' => $claim->id
                            ]) ?>"><i class="material-icons notranslate to-text">done</i>Одобрить
                            </a>
                        <?php endif; ?>
                        <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->id == $claim->user_id ||
                                Yii::$app->user->identity->admin == 1) && $claim->status == 0) : ?>
                            <a href="<?= Url::to(['claims/edit',
                                'claim' => 'vacation',
                                'id' => $claim->id
                            ]) ?>"><i class="material-icons notranslate to-text">edit</i>Редактировать
                            </a>
                        <?php endif; ?>
                        <?php if(User::isAdmin()) : ?>
                            <a onclick='return confirm("Удалить?")' href="<?=Url::to(['claims/remove',
                                'claim' => 'vacation',
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
