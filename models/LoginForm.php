<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model{
    
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules(){
        return [
            [['username', 'password'], 'required', 'message' => 'Обязательное поле'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'rememberMe' => 'Запомнить'
        ];
    }

    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
            }
        }
    }
    
    public function login(){
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
    }
    
    public function getUser(){
        if(filter_var($this->username, FILTER_VALIDATE_EMAIL)){
            $user = User::findOne(['email' => $this->username]);
        }else{
            $user = User::findByUsername($this->username);
        }
        return $user;
    }
}