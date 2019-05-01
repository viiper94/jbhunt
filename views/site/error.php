<?php

$this->title = $exception->statusCode. ' - ' .$exception->getMessage();

if(isset($meta)){
	foreach($meta as $item){
		$this->registerMetaTag($item);
	}
} ?>

<div class="container valign-wrapper">
	<h3 class="white-text text-shadow">Ошибка <?= $this->title ?></h3>
	<?php if(Yii::$app->user->isGuest) : ?>
		<p class="white-text text-shadow fs17">
			<a class="white-text" href="<?= \yii\helpers\Url::to(['site/login']) ?>">Войдите</a>
			на сайт и попробуйте еще раз.
		</p>
	<?php endif ?>
</div>