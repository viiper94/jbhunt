<?php

namespace app\models;

use yii\base\Model;

class PasswordForm extends Model{

	public $password;
	public $password_new;
	public $password_new_2;

	public function rules(){
		return [
			[['password', 'password_new', 'password_new_2'], 'required', 'message' => 'Обязательное поле'],
			['password_new', 'compare', 'compareAttribute' => 'password_new_2', 'message' => 'Повторный пароль не совпадает'],
		];
	}

	public function editPassword(){
		$user = User::findOne(\Yii::$app->user->id);
		if(!$user->password || ($user->password && \Yii::$app->security->validatePassword($this->password, $user->password))){
			$user->password = \Yii::$app->security->generatePasswordHash($this->password_new);
		}
		return $user->update() !== false;
	}

}