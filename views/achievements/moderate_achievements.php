<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Модерация достижений - J.B. Hunt'; ?>

<div class="container">
    <h4 class="light">Модерация достижений</h4>
    <?php if(count($progress) > 0) : ?>
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => 'Начало',
            'lastPageLabel' => 'Конец',
            'options' => [
                'class' => 'pagination center'
            ],
            'prevPageCssClass' => 'waves-effect',
            'pageCssClass' => 'waves-effect',
            'nextPageCssClass' => 'waves-effect',
            'activePageCssClass' => 'active waves-effect',
            'disabledPageCssClass' => 'disabled',
            'maxButtonCount' => 5
        ]) ?>
        <?php foreach($progress as $item) : ?>
            <div class="card horizontal <?php if($item->complete == 0) : ?>grey<?php else: ?>green<?php endif?> lighten-4 hoverable">
                <div class="card-image" style="max-height: 200px;">
					<img src="<?= Yii::$app->request->baseUrl?>/images/achievements/progress/<?= $item->proof ?>" class="materialboxed responsive-img">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="card-title">
                            <a class="black-text" href="<?= Url::to(['members/edit', 'id' => $item->member_id]) ?>">[<?= $item->u_company ?>] <?= $item->u_nickname ?></a>
                            - <a class="black-text" href="<?= Url::to(['achievements/edit', 'id' => $item->ach_id]) ?>"><?= $item->title ?></a></div>
                        <p><?= $item->description ?></p>
                    </div>
					<div class="card-action">
						<?php if($item->complete == 0) : ?>
								<a href="<?= Url::to(['achievements/apply', 'id' => $item->id]) ?>" onclick="return confirm('Одобрить?')">
									<i class="material-icons notranslate to-text">done</i>Одобрить
								</a>
								<a href="<?= Url::to(['achievements/deny', 'id' => $item->id]) ?>" onclick="return confirm('Запись будет удалена безвозвратно. Уверены?')">
									<i class="material-icons notranslate to-text">clear</i>Отклонить
								</a>
						<?php endif ?>
						<a target="_blank" href="<?=Yii::$app->request->baseUrl?>/images/achievements/progress/<?= $item->proof ?>">Оригинал скриншота</a>
						<a href="<?= Url::to(['achievements/delete', 'id' => $item->id]) ?>" onclick="return confirm('Удалить?')">
							<i class="material-icons notranslate to-text">delete</i>Удалить
						</a>
					</div>
                </div>
            </div>
        <?php endforeach ?>
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'firstPageLabel' => 'Начало',
            'lastPageLabel' => 'Конец',
            'options' => [
                'class' => 'pagination center'
            ],
            'prevPageCssClass' => 'waves-effect',
            'pageCssClass' => 'waves-effect',
            'nextPageCssClass' => 'waves-effect',
            'activePageCssClass' => 'active waves-effect',
            'disabledPageCssClass' => 'disabled',
            'maxButtonCount' => 5
        ]) ?>
    <?php else: ?>
        <h5 class="light">Нет достижений для модерации</h5>
    <?php endif ?>
</div>