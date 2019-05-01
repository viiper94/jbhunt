<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model{

    public $username;
    public $email;
    public $password;
    public $password_2;
    public $first_name;
    public $last_name;
    public $birth_date;
    public $country;
    public $city;
    public $vk;
    public $steam;
    public $steamid64;
    public $truckersmp;
    public $visible_truckersmp = false;
    public $visible_steam = true;
    public $nickname;
    public $has_ats;
    public $has_ets;

    public function rules() {
        return [
            [['username', 'email', 'password', 'password_2'], 'required', 'message' => 'Обязательные поля не заполнены'],
            [['password', 'password_2'], 'string', 'min' => 6],
            [['email'], 'email', 'message' => 'Невалидный E-Mail'],
            [['username', 'email', 'password', 'password_2', 'steam', 'vk'], 'trim'],
            [['has_ets', 'has_ats', 'visible_truckersmp', 'visible_steam'], 'boolean'],
            [['first_name', 'last_name', 'country', 'city', 'birth_date', 'nickname', 'steamid64'], 'string'],
            [['vk', 'steam', 'truckersmp'], 'url', 'defaultScheme' => 'https', 'message' => 'Неправильная ссылка'],
            [['email'], 'checkEmail'],
            [['steam'], 'checkSteam'],
            [['vk'], 'checkVk'],
            [['username'], 'checkUsername'],
            ['password', 'compare', 'compareAttribute' => 'password_2', 'message' => 'Повторный пароль не совпадает'],
        ];
    }

    public function checkSteam($attribute, $params) {
        if($this->steam){
            $regex = '%^(https?:\/\/)?steamcommunity\.com\/(id|profiles)\/[^\/]{1,}\/?$%';
            if(!preg_match($regex, $this->steam)){
                $this->addError($attribute, 'Укажите профиль Steam в виде "<b>http://steamcommunity.com/</b><i>id,profiles</i><b>/</b><i>ваш_id</i>"');
            }
        }
    }

    public function checkVk($attribute, $params) {
        if($this->vk){
            $regex = '%^(https?:\/\/)?vk.com\/[^\/]{1,}\/?$%';
            if(!preg_match($regex, $this->vk)){
                $this->addError($attribute, 'Укажите профиль ВК в виде "<b>http://vk.com/</b><i>ваш_id</i>"');
            }
        }
    }

    public function checkUsername($attribute, $params){
        $user = User::findByUsername($this->username);
        if(count($user) > 0){
            $this->addError($attribute, 'Логин уже занят');
        }
    }

    public function checkEmail($attribute, $params){
        $user = User::findOne(['email' => $this->email]);
        if(count($user) > 0){
            $this->addError($attribute, 'Такой E-Mail уже зарегистрирован');
        }
    }

    public function signup(){
        $user = new User();
        $user->email = $this->email;
        $user->username = $this->username;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->country = $this->country;
        $user->city = $this->city;
        $user->vk = $this->vk;
        $user->steam = $this->steam;
        $user->steamid = $this->steamid64;
        $user->truckersmp = $this->truckersmp;
        $user->visible_truckersmp = $this->visible_truckersmp;
        $user->visible_steam = $this->visible_steam;
        $user->nickname = $this->nickname;
        $user->birth_date = $this->birth_date;
        $user->has_ets = $this->has_ets ? '1' : '0';
        $user->has_ats = $this->has_ats ? '1' : '0';
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->registered = date('Y-m-d');
        if($user->save()){
            Mail::newUserToAdmin($user);
            return $user->id;
        }else{
            return false;
        }
    }

}