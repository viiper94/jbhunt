<?php

use yii\helpers\Url;

$this->title = 'TEDIT для ETS2 и ATS - Volvo Trucks';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Tedit для Euro Truck Simulator 2 Multiplayer и American Truck Simulator Multiplayer. Tedit для TruckersMP.'
]);
$this->registerMetaTag([
	'name' => 'keywords',
	'content' => 'tedit, ets2, ats, truckersmp, tedit для ets2mp, tedit для atsmp, как найти прицеп на конвой, как взять груз на конвой'
]);
?>

<div class="parallax-container parallax-shadow hide-on-small-only" style="height: 400px;">
    <div class="container">
        <h4 class="parallax-title light white-text text-shadow">TEDIT - Личный прицеп</h4>
    </div>
    <div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/images/mods/categories/mods-main.jpg"></div>
</div>

<div class="container">
    <div class="card grey lighten-4">
            <div class="card-content">
				<article>
					<p class="fs17"><h5 style="display: inline-block; margin-bottom: 0;"><b>TEDIT</b></h5> - замечательный оффлайн инструмент,
					призваный в корне изменить геймплей игры путем покупки собственного прицепа.<br>
					В мире TruckersMP TEDIT получил широкое расспостранение благодаря возможности легко и быстро "взять" нужный прицеп,
					даже тот, который не доступен стандартными средствами,
					обходя при этом ограничения игры (отсутствие маршрута груза на внутриигровой карте, возможность перемещения между гаражами).
					В отличии от модов, пользоваться TEDIT можно прямо в мультиплеере, без перезапуска игры.</p>
				</article>
				<h5><b>Установка и работа с TEDIT</b></h5>
				<ol>
					<li>Открыть <b>config.cfg</b> через текстовый редактор и в параметре <b>g_save_format</b> изменить
						значение <b>0</b> на <b>2</b>, перезапустить игру, сохраниться.</li>
					<li>Скопировать содержимое соответствующей папки из архива в любое удобное место.</li>
					<li>При первом запуске необходимо указать папку с сохранением к которому хотим применить TEDIT.</li>
					<li>Перезагружаем сейв в программе кнопкой <b>Reload</b>, если необходимо.</li>
					<li>Покупаем прицеп, берем груз, сохраняем кнопкой <b>Save game.sii</b> (подробнее на видео ниже)</li>
					<li>Переходим в игру, загружаем то же сохранение.</li>
				</ol>
                <ul class="collapsible" data-collapsible="accordion">
                    <li>
                        <div class="collapsible-header grey lighten-4"><i class="material-icons notranslate">ondemand_video</i>Euro Truck Simulator 2</div>
                        <div class="collapsible-body">
                            <div class="video-container">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/XOYwF48E_PU?rel=0" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header grey lighten-4"><i class="material-icons notranslate">ondemand_video</i>American Truck Simulator</div>
                        <div class="collapsible-body">
                            <div class="video-container">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/TwS63ASk36E?rel=0" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </li>
                </ul>
				<article>
					<i class="material-icons notranslate left orange-text">warning</i>
					<span class="bold">Внимание! Программа предназначена только для продвинутых пользователей!</span><br>
					Для безпроблемного пользования необходимо:
					<ul class="browser-default" style="margin-top: 0; margin-left: 15px;">
						<li>знать элементарную структуру игры</li>
						<li>быть знакомым с понятием "def"</li>
						<li>понимать последствия внесенных изменений</li>
					</ul>
				</article>
				<p class="grey-text">Автор программы: knox_xss</p>
            </div>
			<div class="card-action">
				<a class="btn indigo darken-3 waves-effect waves-light" href="<?= Yii::$app->request->baseUrl ?>/tedit_v6.14.2.rar" target="_blank">
					<i class="material-icons notranslate left">file_download</i>
					Скачать TEDIT v6.14.2
				</a>
				<a href="http://truck-sim.club/topic/21801-svoi-pritcep-tedit-v6112-reliz-i-obschee-obsuzhdenie/?p=371683" class="right btn-flat" target="_blank">
					Всегда свежая версия
				</a>
			</div>
        </div>
    </div>
</div>