<?php

$this->title = 'Экзамены для водителей Volvo Trucks';

?>

<div class="parallax-container parallax-shadow" style="height: 400px;">
	<div class="container">
		<h4 class="parallax-title light white-text text-shadow"><?= $this->title ?></h4>
	</div>
	<div class="parallax"><img src="<?=Yii::$app->request->baseUrl?>/assets/img/exams/exams.jpg"></div>
</div>
<div class="container">
	<div class="card horizontal grey lighten-4 hoverable">
		<div class="card-image">
			<img src="<?=Yii::$app->request->baseUrl?>/assets/img/exams/3.jpg"  class="materialboxed responsive-img">
		</div>
		<div class="card-stacked">
			<div class="card-content">
				<div class="card-title">Экзамен на 3 категорию</div>
				<p>Cдается в порту города Dover в Англии.<br>
					Время - 7 мин<br>
					Ошибок - 3 (+30 сек)<br>
					Сдается с шторным полуприцепом</p>
			</div>
		</div>
	</div>
	<div class="card horizontal grey lighten-4 hoverable">
		<div class="card-image">
			<img src="<?=Yii::$app->request->baseUrl?>/assets/img/exams/2.jpg"  class="materialboxed responsive-img">
		</div>
		<div class="card-stacked">
			<div class="card-content">
				<div class="card-title">Экзамен на 2 категорию</div>
				<p>Cдается в порту Ijmuiden (около города Амстердам).<br>
					Время - 10 мин<br>
					Ошибок - 3 (+20 сек)<br>
					Сдается с шторным полуприцепом</p>
			</div>
		</div>
	</div>
	<div class="card horizontal grey lighten-4 hoverable">
		<div class="card-image">
			<img src="<?=Yii::$app->request->baseUrl?>/assets/img/exams/1.jpg"  class="materialboxed responsive-img">
		</div>
		<div class="card-stacked">
			<div class="card-content">
				<div class="card-title">Экзамен на 1 категорию</div>
				<p>Cдается в контейнерном терминале города Esbjerg (DLC Scandinavia).<br>
					Время - 20 мин<br>
					Ошибок - 5 (+20 сек)<br>
					Сдается с шторным полуприцепом</p>
			</div>
		</div>
	</div>
</div>