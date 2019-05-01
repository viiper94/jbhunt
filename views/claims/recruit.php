<?php

use yii\helpers\Url;
use app\models\User; ?>

<div id="recruit">

    <div class="valign-wrapper" style="justify-content: space-between">
        <h5 class="light">Заявления на вступление</h5>
        <?php if(!User::isVtcMember()) : ?>
            <a href="<?= Url::to(['site/recruit']) ?>" class="btn indigo waves-effect waves-light">
                Вступить в ВТК<i class="material-icons notranslate right">add_circle</i>
            </a>
        <?php endif ?>
    </div>

    <?php foreach($recruits as $recruit) :
        switch ($recruit->status){
            case '1': $color_class = 'light-green lighten-4-5'; break;
            case '2': $color_class = 'red lighten-4-5'; break;
            case '3': $color_class = 'yellow lighten-5'; break;
            case '0':
            default : $color_class = 'grey lighten-4'; break;
        } ?>
        <div class="card horizontal hoverable <?= $color_class ?>">
            <div class="card-image grey lighten-4 no-img_horizontal" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/<?= $recruit->picture ?>)">
                <a href="<?= Url::to(['site/profile', 'id' => $recruit->user_id]) ?>" class="waves-effect waves-light"></a>
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <a class="card-title black-text" href="<?= Url::to(['site/profile', 'id' => $recruit->user_id]) ?>">
						<?= $recruit->first_name ?> <?= $recruit->last_name ?>
					</a>
                    <div class="flex claim-info">
                        <div style="max-width: 70%">
                            <p class="nowrap"><?= Yii::$app->formatter->asDate($recruit->date, 'long') ?></p>
                            <?php if($recruit->hear_from && $recruit->hear_from != 'Другой вариант') : ?>
                                <p><b>Откуда узнали?</b> <?= strip_tags($recruit->hear_from) ?></p>
                            <?php endif ?>
                            <?php if($recruit->invited_by) : ?>
                                <p><b>Кто пригласил?</b>
									<a href="<?= Url::to(['site/profile', 'id' => $recruit->i_id]) ?>" style="color: inherit">
										<?= ($recruit->i_company ? '['.$recruit->i_company.'] ' : ' ').$recruit->i_nickname ?>
									</a>
								</p>
                            <?php endif ?>
                            <?php if($recruit->comment) : ?>
                                <p><b>Комментарий:</b> <?= strip_tags($recruit->comment, '<br>'); ?></p>
                            <?php endif ?>
                        </div>
                        <div class="claim-status" style="flex: 1;">
                            <p><b class="fs17"><?= $recruit->getStatusTitle() ?></b><br><?= str_replace(',', '<br>', $recruit->reason) ?></p>
                            <?php if($recruit->viewed): ?>
                                <a class="grey-text" href="<?= Url::to(['site/profile', 'id' =>$recruit->viewed]) ?>">
									Рассмотрел: <?= $recruit->a_first_name ?> <?= $recruit->a_last_name ?>
								</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->id == $recruit->user_id ||
                        Yii::$app->user->identity->admin == 1) && $recruit->status == 0 || User::isAdmin()) : ?>
                    <div class="card-action">
                        <?php if(User::isAdmin() && $recruit->status == 0) : ?>
                            <a onclick='return confirm("Одобрить заявку?")' href="<?= Url::to(['claims/apply',
                                'claim' => 'recruit',
                                'id' => $recruit->id
                            ]) ?>"><i class="material-icons notranslate to-text">done</i>Одобрить
                            </a>
                        <?php endif; ?>
                        <?php if(!Yii::$app->user->isGuest && (Yii::$app->user->id == $recruit->user_id ||
                                Yii::$app->user->identity->admin == 1) && $recruit->status == 0) : ?>
                            <a href="<?= Url::to(['claims/edit',
                                'claim' => 'recruit',
                                'id' => $recruit->id
                            ]) ?>"><i class="material-icons notranslate to-text">edit</i>Редактировать
                            </a>
                        <?php endif; ?>
                        <?php if(User::isAdmin()) : ?>
                            <a onclick='return confirm("Удалить?")' href="<?=Url::to(['claims/remove',
                                'claim' => 'recruit',
                                'id' => $recruit->id
                            ]) ?>"><i class="material-icons notranslate to-text">delete</i>Удалить
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>

</div>