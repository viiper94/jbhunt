<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Вариации тягачей - Volvo Trucks';

?>

<div class="container">
    <div class="row" style="margin-top: 20px;">
        <?php $game = Yii::$app->request->get('game'); ?>
        <div class="col m6 s12">
            <a href="<?= Url::to(['site/variations', 'game' => 'ets2']) ?>" class="btn-flat waves-effect right<?php if($game != 'ats'): ?> disabled<?php endif ?>">
                Отделение ETS2
            </a>
        </div>
        <div class="col m6 s12">
            <a href="<?= Url::to(['site/variations', 'game' => 'ats']) ?>" class="btn-flat waves-effect left<?php if($game == 'ats'): ?> disabled<?php endif ?>">
                Отделение ATS
            </a>
        </div>
    </div>
    <div class="card-panel grey lighten-4">
        <h5 class="light"><i class="material-icons notranslate small left">info</i>По просторам Америки мы ездим на всех тягачах в нашем фирменном цвете.</h5>
    </div>
    <img src="<?= Yii::$app->request->baseUrl ?>/assets/img/variations/var_us.jpg" class="materialboxed responsive-img z-depth-3" style="margin-bottom: 20px;">
</div>