<?php

use yii\helpers\Url;
use app\models\User;;
use yii\widgets\LinkPager;

$this->title = 'Трейлеры - Volvo Trucks';
Url::remember();
?>

<div class="container row">
    <div class="col s12 m6">
        <div class="card-panel grey lighten-4 search">
            <form method="get">
                <div class="input-field">
                    <button type="submit" class="prefix user-search waves-effect circle">
                        <i class="material-icons notranslate">search</i>
                    </button>
                    <input placeholder="Искать трейлер" type="text" name="q" <?php if(Yii::$app->request->get('q')): ?>value="<?= Yii::$app->request->get('q') ?>"<?php endif ?>>
                    <?php if(Yii::$app->request->get('q')) : ?>
                        <a href="<?= Url::to(['trailers/index']) ?>" class="search-reset waves-effect circle">
                            <i class="material-icons notranslate">clear</i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <div class="order col m4 s12 right-align">
        <div class="input-field right-align">
            <select id="category-select" data-url="<?= Url::to(['trailers/index']) ?>/">
                <option value="" <?= Yii::$app->request->get('category') ? '' : 'selected' ?>>Все прицепы</option>
                <?php foreach($categories as $name => $cat): ?>
                    <option value="<?= $name ?>" <?= Yii::$app->request->get('category') == $name ? 'selected' : '' ?>><?= $cat['title'] ?></option>
                <?php endforeach ?>
            </select>
            <label>Фильтрация по категориям</label>
        </div>
    </div>
    <div class="order col m2 s12 right-align">
        <div class="input-field right-align">
            <select id="game-select" data-url="<?= Url::to(['trailers/index']) ?>/">
                <option value="" <?= Yii::$app->request->get('category') ? '' : 'selected' ?>>Все игры</option>
				<option value="ets" <?= Yii::$app->request->get('game') == 'ets' ? 'selected' : '' ?>>ETS2</option>
				<option value="ats" <?= Yii::$app->request->get('game') == 'ats' ? 'selected' : '' ?>>ATS</option>
            </select>
            <label>Фильтрация по играх</label>
        </div>
    </div>
    <h5 class="light col s12 m6">Всего <?= $total ?></h5>
    <?= LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel' => 'Начало',
        'lastPageLabel' => 'Конец',
        'options' => [
            'class' => 'pagination right-align col m6 s12'
        ],
        'prevPageCssClass' => 'waves-effect',
        'pageCssClass' => 'waves-effect',
        'nextPageCssClass' => 'waves-effect',
        'activePageCssClass' => 'active waves-effect',
        'disabledPageCssClass' => 'disabled',
        'maxButtonCount' => 5
    ]) ?>
    <div class="clearfix"></div>
    <?php foreach ($trailers as $key => $trailer): ?>
        <div class="col l6 m6 s12">
            <div class="card grey lighten-4">
                <div class="card-image">
                    <img class="materialboxed" src="<?= Yii::$app->request->baseUrl ?>/images/trailers/<?= $trailer->picture ?>" alt="">
                </div>
                <div class="card-content">
                    <h5 class="light"><?=  $trailer->name ?></h5>
                    <p><?= $trailer->description ?></p>
                    <?php if($trailer->category) : ?>
                        <span class="grey-text">Категория: <?= $categories[$trailer->category]['title'] ?></span>
                    <?php endif ?>
                    <?php if($trailer->weight) : ?>
                        <span class="grey-text right">Вес груза (в тоннах): <?= $trailer->weight ?></span>
                    <?php endif ?>
                </div>
                <?php if(User::isAdmin()): ?>
                    <div class="card-action">
                        <a href="<?= Url::to(['trailers/edit', 'id' => $trailer->id]) ?>">Редактировать</a>
                        <a href="<?= Url::to(['trailers/remove', 'id' => $trailer->id]) ?>" onclick="return confirm('Удалить трейлер?')">Удалить</a>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <?php if($key % 2 != 0) : ?>
            <div class="clearfix"></div>
        <?php endif ?>
    <?php endforeach; ?>
    <div class="clearfix"></div>
    <?php if(\app\models\User::isAdmin()) : ?>
        <div class="fixed-action-btn">
            <a href="<?=Url::to(['trailers/add'])?>" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons notranslate">add</i></a>
        </div>
    <?php endif; ?>
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
</div>