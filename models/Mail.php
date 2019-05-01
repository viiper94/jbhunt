<?php

namespace app\models;


use Yii;
use yii\helpers\Url;

class Mail{

	public static $to = [
		"viiper94@gmail.com", // Mayday
		"a.borisov97@mail.ru", // Canyon
	];

    public static function newUserToAdmin($data = []){

        $subject = "Новый пользователь на сайте VolvoVTC.com";

        Yii::$app->mailer->compose('admin/newuser', [
            'data' => $data
        ])->setFrom('info@volvovtc.com')
            ->setTo(self::$to)
            ->setSubject($subject)
            ->send();

        return true;
    }

    public static function newClaimToAdmin($claim, $data = [], $user){

        $subject = "Заявление $claim на сайте VolvoVTC.com";

        Yii::$app->mailer->compose('admin/newclaim', [
            'claim' => $claim,
            'user' => $user,
            'data' => $data
        ])->setFrom('info@volvovtc.com')
            ->setTo(self::$to)
            ->setSubject($subject)
            ->send();

        return true;
    }

    public static function sendResetPassword($string, $email){

        $subject = "Сброс пароля на сайте VolvoVTC.com";

        Yii::$app->mailer->compose('user/reset_pwd', [
            'email' => $email,
            'url' => 'https://volvovtc.com/reset?u='.$string,
        ])->setFrom('info@volvovtc.com')
            ->setTo($email)
            ->setSubject($subject)
            ->send();

        return true;
    }

    public static function newAppeal($appeal, $uid){

        $subject = "Новая жалоба на сайте VolvoVTC.com";

        Yii::$app->mailer->compose('admin/newappeal', [
            'appeal' => $appeal,
            'user' => User::findOne($uid),
            'subject' => $subject
        ])->setFrom('info@volvovtc.com')
            ->setTo(self::$to)
            ->setSubject($subject)
            ->send();

        return true;
    }

	public static function newMemberConvoyToAdmin($cid){

		$subject = 'Конвой от [Volvo Trucks] '.Yii::$app->user->identity->nickname . ' на сайте VolvoVTC.com';

		Yii::$app->mailer->compose('admin/newconvoy', [
			'convoy_id' => $cid
		])->setFrom('info@volvovtc.com')
			->setTo(self::$to)
			->setSubject($subject)
			->send();

		return true;
    }

	public static function newAchievementToAdmin(){

		$subject = 'Достижение ожидает модерации на сайте VolvoVTC.com';

		Yii::$app->mailer->compose('admin/newachievement')->setFrom('info@volvovtc.com')
			->setTo(self::$to)
			->setSubject($subject)
			->send();

		return true;
    }

}