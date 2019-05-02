<?php

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this); ?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=Yii::$app->request->baseUrl?>/favicon.ico" />
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-99570317-1', 'auto');
        ga('send', 'pageview');
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">

    <?php require_once 'navbar_mobile.php' ?>
    <?php require_once 'navbar.php' ?>

    <main class="<?= Yii::$app->controller->id ?> <?= Yii::$app->controller->action->id ?>">

        <?= $content ?>

			<div class="clearfix"></div>

    </main>

    <?php require_once 'footer.php' ?>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>