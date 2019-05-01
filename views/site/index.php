<?php

use yii\helpers\Url;

$this->title = 'ВТК Volvo Trucks - TruckersMP';
$this->registerMetaTag([
    'name' => 'description',
    'content' => 'ВТК Volvo Trucks - это динамичная, прогрессивная виртуальная транспортная компания - 
    сообщество профессиональных водителей Euro Truck Simulator 2 и American Truck Simulator. 
    Вы увидите не только массу различных мероприятий, 
    но и сможете найти компаньонов для веселых поездок по дорогам Европы или США.'
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'втк, виртуальная транспортная компания, ets2, ats, truckersmp, моды для ets2mp, моды для atsmp'
]);
?>

<div id="index-banner" class="parallax-container valign-wrapper">
    <div class="section" style="flex: 1;">
        <div class="container white-text">
            <h1 class="center text-shadow">ВТК J.B. Hunt</h1>
            <div class="row center">
                <h6 class="col offset-l3 l6 s12 text-shadow">Виртуальная транспортная компания J.B. Hunt -
                    это сообщество профессиональных водителей Euro Truck Simulator 2 и American Truck Simulator.</h6>
            </div>
            <?php if(Yii::$app->user->isGuest) : ?>
                <div class="row center">
                    <a href="<?=Url::to(['site/login'])?>" class="btn-large waves-effect waves-light indigo darken-3">Войти</a>
                </div>
            <?php endif; ?>
            <?php if(!\app\models\User::isVtcMember() && !Yii::$app->user->isGuest) : ?>
                <div class="row center">
                    <a href="<?=Url::to(['site/recruit'])?>" class="btn-large waves-effect waves-light indigo darken-3">Вступить</a>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="parallax"><img src="assets/img/home/1-1.jpg"></div>
</div>
<section class="z-depth-4" style="padding-bottom: 50px;">
    <div class="container">
        <div class="row">
            <div class="col s12 m4">
                <div class="center">
                    <h2 class="indigo-text darken-3"><i class="material-icons notranslate">local_shipping</i></h2>
                    <h5>Конвои</h5>
                    <p class="light fs17">Наши конвои не просто бездумные поездки,
                        а тщательно продуманные маршруты по живописным местам Европы и некоторым штатам США.
                        Живое общение и ламповая атмосфера поможет скоротать время во время длительных рейсов,
                        а также найти новых друзей объединенных одним интересом - Volvo и большие машины.</p>
                    <a href="<?= Url::to(['convoys/index']) ?>" class="btn indigo darken-3 waves-effect waves-light">Конвои</a>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="center">
                    <h2 class="indigo-text darken-3"><i class="material-icons notranslate">group</i></h2>
                    <h5>Квалификация</h5>
                    <p class="light fs17">Что важно для любой команды? Любой из Вас скажет это опыт и этого не отнять у Volvo Trucks.
                        В нашей команде исключительно профессиональные водители ETS2,
                        готовые выполнить доставку любой сложности в любой уголок игрового мира. Только с нами Вы узнаете то,
                        чего не узнаете с другими. Наш девиз подтвержден годами существования ВТК: Опыт, точность, дисциплина!</p>
                    <a href="<?= Url::to(['site/rules']) ?>" class="btn indigo darken-3 waves-effect waves-light">Правила</a>
                </div>
            </div>
            <div class="col s12 m4">
                <div class="center">
                    <h2 class="indigo-text darken-3"><i class="material-icons notranslate">flash_on</i></h2>
                    <h5>Узнаваемость</h5>
                    <p class="light fs17">Наша Компания отличается высокой дисциплиной,
                        качеством перевозок и взаимовыручкой между водителями.
                        Каждый сотрудник Volvo Trucks выделяется на дороге,
                        благодаря строгому фирменному стилю тягача и безупречной манере вождения.</p>
                    <a href="<?= Url::to(['gallery/index']) ?>" class="btn indigo darken-3 waves-effect waves-light">Галерея</a>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="parallax-container valign-wrapper">
    <div class="parallax"><img src="assets/img/home/2.jpg"></div>
</div>
<section class="z-depth-4" style="padding: 50px 0">
    <div class="container">
        <div class="row">
            <div class="col m6 s12 text-center">
                <h5 class="">Особенности быть сотрудником ВТК:</h5>
                <ul class="browser-default light fs17">
                    <li>Ежедневные конвои</li>
                    <li>Новые друзья и коллеги</li>
                    <li>Всегда найдете с кем можно прокатиться или поиграть в другие игры</li>
                    <li>Интересный карьерный рост</li>
                    <li>Увлекательные мероприятия и конкурсы</li>
                </ul>
            </div>
            <div class="col m6 s12">
                <h5 class="">В открытой группе Вы можете найти:</h5>
                <ul class="browser-default light fs17">
                    <li>Еженедельный открытый конвой для всех желающих</li>
                    <li>Решение проблем (ETS2, ATS, TruckersMP)</li>
                    <li>Свежие новости</li>
                    <li>Попутчиков</li>
                    <li>Фотоотчеты конвоев и мероприятий</li>
                    <li>Красивые скриншоты из мира ETS2 и ATS</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<div class="parallax-container valign-wrapper">
    <div class="parallax"><img src="assets/img/home/3.jpg"></div>
</div>