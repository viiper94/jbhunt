<?php


$this->title = 'Заявления Volvo Trucks';
?>

<div class="parallax-container parallax-shadow" style="height: 400px;">
    <div class="container">
        <h4 class="parallax-title light white-text text-shadow">Заявления Volvo Trucks</h4>
    </div>
    <div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/assets/img/claims.jpg"></div>
</div>
<div class="container claims">
    <div class="row">
        <div class="col s12">
            <ul class="tabs tabs-fixed-width">
                <li class="tab">
                    <a href="#recruit">
                        На вступление
                        <?php if(\app\models\User::isAdmin()){
                            $count = \app\controllers\ClaimsController::countClaims($recruits);
                            if($count > 0) : ?>
                                <span class="claims-count circle indigo darken-3 white-text"><?= $count ?></span>
                            <?php endif;
                        } ?>
                    </a>
                </li>
                <li class="tab">
                    <a href="#dismissal">
                        На увольнение
                        <?php if(\app\models\User::isAdmin()){
                            $count = \app\controllers\ClaimsController::countClaims($fired);
                            if($count > 0) : ?>
                                <span class="claims-count circle indigo darken-3 white-text"><?= $count ?></span>
                            <?php endif;
                        } ?>
                    </a>
                </li>
                <li class="tab">
                    <a href="#nickname">
                        На смену никнейма
                        <?php if(\app\models\User::isAdmin()){
                            $count = \app\controllers\ClaimsController::countClaims($nickname);
                            if($count > 0) : ?>
                                <span class="claims-count circle indigo darken-3 white-text"><?= $count ?></span>
                            <?php endif;
                        } ?>
                    </a>
                </li>
                <li class="tab">
                    <a href="#vacation">
                        На отпуск
                        <?php if(\app\models\User::isAdmin()){
                            $count = \app\controllers\ClaimsController::countClaims($vacation);
                            if($count > 0) : ?>
                                <span class="claims-count circle indigo darken-3 white-text"><?= $count ?></span>
                            <?php endif;
                        } ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php include_once 'recruit.php' ?>
    <?php include_once 'fired.php' ?>
    <?php include_once 'nickname.php' ?>
    <?php include_once 'vacation.php' ?>
</div>