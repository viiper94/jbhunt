<h4>Новая жалоба на сотрудника [Volvo Trucks] <?= $user->nickname ?> на сайте:</h4>
<p>Описание: <?= $appeal->description ?><br>
    Доказательства: <?= $appeal->proof ?></p>
<p><a href="https://volvovtc.com/appeals" target="_blank">Смотреть все жалобы</a></p>
Дата жалобы: <?php $date = new DateTime($appeal->date); echo $date->format('d.m.y H:i'); ?>