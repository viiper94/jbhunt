<?php use yii\helpers\Url; ?>

<footer class="page-footer grey lighten-3 ">
    <div class="container">
        <div class="row flex">
            <div class="footer-left black-text">
                <div class="col m4 s12">
                    <p><i class="material-icons notranslate left">featured_play_list</i>ВАКАНСИИ</p>
                    <ul>
                        <li><a href="<?= Url::to(['site/recruit']) ?>">Вступить в ВТК</a></li>
                        <li><a href="<?= Url::to(['site/members']) ?>">Список сотрудников</a></li>
                    </ul>
                    <p><i class="material-icons notranslate left">note_add</i>ЗАЯВЛЕНИЯ</p>
                    <ul>
                        <li><a href="<?= Url::to(['claims/index']) ?>">Написать заявление</a></li>
                        <li><a href="<?= Url::to(['appeals/add']) ?>">Жалоба на водителя</a></li>
                    </ul>
                </div>
                <div class="col m4 s12">
                    <p><i class="material-icons notranslate left">error</i>ПРАВИЛА</p>
                    <ul>
                        <li><a href="<?= Url::to(['site/rules']) ?>">Правила компании</a></li>
                        <li><a href="https://forum.truckersmp.com/index.php?/topic/5807-in-game-rules-rus-игровые-правила-08-06-2017/" target="_blank">Правила TruckersMP</a></li>
                    </ul>
                    <p><i class="material-icons notranslate left">settings</i>МОДИФИКАЦИИ</p>
                    <ul>
                        <li><a href="<?= Url::to(['modifications/index', 'game' => 'ets']) ?>">Моды для ETS2MP</a></li>
                        <li><a href="<?= Url::to(['modifications/index', 'game' => 'ats']) ?>">Моды для ATSMP</a></li>
                    </ul>
                </div>
                <div class="col m4 s12">
                    <p><i class="material-icons notranslate left">mic</i>СВЯЗЬ</p>
                    <ul>
                        <li><a href="https://www.teamspeak.com/en/downloads" target="_blank">Скачать TeamSpeak3</a></li>
                        <li class="fs17">Наш адрес: <b><a href="ts3server://volvotrucks.ts-3.top">volvotrucks.ts-3.top</a></b></li>
                    </ul>
                    <p><i class="material-icons notranslate left">lightbulb_outline</i>ФИРМЕННЫЙ СТИЛЬ</p>
                    <ul>
                        <li><a href="<?= Url::to(['site/variations', 'game' => 'ets2']) ?>">Отделение ETS2</a></li>
                        <li><a href="<?= Url::to(['site/variations', 'game' => 'ats']) ?>">Отделение ATS</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-right right-align">
                <div class="footer-top">
                    <h6 class="black-text">МЫ В СОЦИАЛЬНЫХ СЕТЯХ</h6>
                    <ul class="socials links">
                        <li class="vk"><a class="waves-effect circle" target="_blank" href="https://vk.com/volvo_trucks_russia"></a></li>
                        <li class="steam"><a class="waves-effect circle" target="_blank" href="http://steamcommunity.com/groups/volvo_trucks"></a></li>
                        <li class="instagram"><a class="waves-effect circle" target="_blank" href="https://instagram.com/volvo_trucks_russia"></a></li>
                        <li class="youtube"><a class="waves-effect circle" target="_blank" href="https://www.youtube.com/channel/UCCUkLXBObH0IA54XhCaDTzg"></a></li>
                    </ul>
                </div>
                <div class="footer-bottom">
                    <h6 class="black-text">ПОЛЕЗНЫЕ ССЫЛКИ</h6>
                    <ul class="links adds">
                        <li class="truckersmp"><a class="waves-effect" target="_blank" href="https://truckersmp.com/"></a></li>
                        <li class="scs"><a class="waves-effect" target="_blank" href="http://blog.scssoft.com/"></a></li>
                        <li class="ets"><a class="waves-effect" target="_blank" href="https://www.eurotrucksimulator2.com/"></a></li>
                        <li class="ats"><a class="waves-effect" target="_blank" href="http://www.americantrucksimulator.com/"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright grey lighten-2 black-text">
        <div class="container"><span>&copy; ВТК "VOLVO TRUCKS" - <?=date('Y')?></span></div>
    </div>
</footer>