<?php

namespace app\models;

use yii\db\ActiveRecord;

class Claims extends ActiveRecord{

	public function getStatusTitle(){
		switch ($this->status){
			case '1': return 'Одобрено'; break;
			case '2': return 'Отказ'; break;
			case '3': return 'На удержании'; break;
			case '0':
			default : return 'Рассматривается'; break;
		}
	}

}