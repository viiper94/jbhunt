<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Жалобы на сотрудников - J.B. Hunt'; ?>

<div class="container">
    <?php if(count($appeals) > 0) : ?>
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => 'Начало',
            'lastPageLabel' => 'Конец',
            'options' => [
                'class' => 'pagination center col m6 s12'
            ],
            'prevPageCssClass' => 'waves-effect',
            'pageCssClass' => 'waves-effect',
            'nextPageCssClass' => 'waves-effect',
            'activePageCssClass' => 'active waves-effect',
            'disabledPageCssClass' => 'disabled',
            'maxButtonCount' => 5
        ]) ?>
        <?php foreach($appeals as $appeal): ?>
            <div class="card horizontal hoverable yellow lighten-4<?php if($appeal->viewed == '0'): ?> grey<?php endif ?>">
                <div class="card-image no-img_horizontal" style="background-image: url(<?=Yii::$app->request->baseUrl?>/images/users/<?= $appeal->appealed_user_picture ?>)">
					<a href="<?= Url::to(['site/profile', 'id' => $appeal->appeal_to_user_id]) ?>"></a>
				</div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h5 class="light">Жалоба на
                            <?php if(\app\models\User::isVtcMember($appeal->appeal_to_user_id)) : ?>
                                <a href="<?= Url::to(['members/edit', 'id' => $appeal->appeal_to_id]) ?>" class="black-text">
                                    <?= '[J.B. Hunt] ' ?><?= $appeal->appealed_user_nickname ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= Url::to(['site/profile', 'id' => $appeal->appeal_to_user_id]) ?>" class="black-text">
                                    <?php if($appeal->appealed_user_company) : ?>
                                        <?= '['.$appeal->appealed_user_company.'] ' ?>
                                    <?php endif ?>
                                    <?= $appeal->appealed_user_nickname ?>
                                </a>
                            <?php endif ?>
                        </h5>
                        <p class="grey-text">
                            <?php if($appeal->is_anonymous == '0') : ?>
                                От
								<a href="<?= Url::to(['site/profile', 'id' => $appeal->uid]) ?>" class="grey-text">
									<?= $appeal->from_user_first_name . ' ' . $appeal->from_user_last_name ?>
									(<?php if($appeal->from_user_company) : ?>[<?= $appeal->from_user_company ?>]<?php endif ?>
									<?= $appeal->from_user_nickname ?>)
								</a>
                            <?php else: ?>
                                Анонимная жалоба
                            <?php endif ?>
                        </p>
						<p><b>Дата:</b> <?= Yii::$app->formatter->asDate($appeal->date, 'php:j F Y') ?></p>
                        <p><b>Описание:</b> <?= $appeal->description ?></p>
                        <p><b>Доказательства:</b> <?= $appeal->proof ?></p>
                    </div>
                    <div class="card-action">
                        <?php if($appeal->viewed == '0'): ?>
                            <a href="<?= Url::to(['appeals/viewed', 'id' => $appeal->id]) ?>">Просмотрено</a>
                        <?php endif ?>
                        <a href="<?= Url::to(['appeals/remove', 'id' => $appeal->id]) ?>">Удалить</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => 'Начало',
            'lastPageLabel' => 'Конец',
            'options' => [
                'class' => 'pagination center col m6 s12'
            ],
            'prevPageCssClass' => 'waves-effect',
            'pageCssClass' => 'waves-effect',
            'nextPageCssClass' => 'waves-effect',
            'activePageCssClass' => 'active waves-effect',
            'disabledPageCssClass' => 'disabled',
            'maxButtonCount' => 5
        ]) ?>
    <?php else: ?>
		<div class="card-panel grey lighten-4">
			<h5 class="card-title light">Нет жалоб</h5>
		</div>
    <?php endif ?>
</div>