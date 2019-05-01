<?php

namespace app\models;

class ClaimsRecruit extends Claims{

	// invited
	public $i_id;
	public $i_company;
	public $i_nickname;

	// user
	public $uid;
	public $first_name;
	public $last_name;
	public $company;
	public $nickname;
	public $registered;
	public $birth_date;
	public $city;
	public $country;
	public $truckersmp;
	public $steam;
	public $vk;
	public $picture;
	public $last_active;

	// viewed
	public $a_first_name;
	public $a_last_name;

    public function rules(){
        return [
            [['user_id'], 'required'],
            [['user_id', 'status', 'viewed', 'invited_by', 'ets_playtime', 'ats_playtime'], 'integer'],
            [['date'], 'safe'],
            [['hear_from', 'reason'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 512],
        ];
    }

	public static function getClaims($limit = null){
		$claims = ClaimsRecruit::find()
			->select([
				'claims_recruit.*',
				'users.first_name',
				'users.last_name',
				'users.picture',
				'admin.first_name as a_first_name',
				'admin.last_name as a_last_name',
				'invited.nickname as i_nickname',
				'invited.company as i_company',
				'invited.id as i_id'
			])
			->innerJoin('users', 'users.id = claims_recruit.user_id')
			->leftJoin('users as admin', 'admin.id = claims_recruit.viewed')
			->leftJoin('vtc_members', 'vtc_members.id = claims_recruit.invited_by')
			->leftJoin('users as invited', 'invited.id = vtc_members.user_id')
			->orderBy(['id'=> SORT_DESC]);
		if($limit) $claims = $claims->limit($limit);
		return $claims = $claims->all();
    }

	public function getReasonList(){
		return [
			'steam' => 'п.2.6.1 - Неверный профиль Steam',
			'steam2' => 'п.2.6.2 - Профиль Steam скрыт',
			'vk' => 'п.2.6.3 - Неверный профиль ВКонтакте',
			'truckersmp' => 'п.2.6.4 - Отсутствие профиля TruckersMP',
			'vk2' => 'п.2.6.5 - Личные сообщения ВКонтакте закрыты',
			'age' => 'п.2.6.6 - Возраст',
			'vtc' => 'п.2.6.7 - Другая ВТК',
			'birthdate' => 'п.2.6.8 - Неверная дата рождения',
			'rep-' => 'п.2.6.9 - Плохая репутация',
		];
    }

}