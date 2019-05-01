<h4>Новый пользователь зарегистрировался на сайте:</h4>
<p>Имя: <?= $data->first_name ?><br>
Фамилия: <?= $data->last_name ?><br>
Никнейм: <?= $data->nickname ?></p>
<p><a href="https://volvovtc.com/profile/<?= $data->id ?>" target="_blank">Смотреть профиль</a></p>
<p>Зарегестрирован <?= date('d.m.Y') ?> в <?= date('H:i') ?></p>