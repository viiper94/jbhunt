<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ResetForm extends Model{

    public $password;
    public $password_2;

    public function rules(){
        return [
            [['password_2', 'password'], 'string'],
            [['password_2'], 'checkPasswords']
        ];
    }

    public function checkPasswords($attribute, $params) {
        if($this->password != $this->password_2){
            $this->addError($attribute, 'Проверочный пароль не совпадает');
        }
    }

    public function attributeLabels() {
        return [
            'password' => 'Новый пароль',
            'password_2' => 'Повторите новый пароль',
        ];
    }

    public function saveNewPassword($string){
        $user = User::findOne(['password_reset' => $string]);
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->password_reset = null;
        return $user->update() !== false;
    }

}