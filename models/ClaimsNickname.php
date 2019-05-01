<?php

namespace app\models;

class ClaimsNickname extends Claims{

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

	public $v_member_id;

    public function rules(){
        return [
            [['member_id', 'viewed'], 'integer'],
            [['new_nickname', 'old_nickname'], 'required'],
            [['date'], 'safe'],
            [['new_nickname', 'status', 'old_nickname'], 'string', 'max' => 45],
        ];
    }

	public static function getClaims($limit = null){
		$claims = ClaimsNickname::find()
			->select([
				'users.*',
				'users.id as uid',
				'claims_nickname.*',
				'admin.first_name as a_first_name',
				'admin.last_name as a_last_name',
				'vtc_members.id as v_member_id'
			])
			->innerJoin('users', 'users.id = claims_nickname.user_id')
			->leftJoin('users as admin', 'admin.id = claims_nickname.viewed')
			->leftJoin('vtc_members', 'vtc_members.id = claims_nickname.member_id')
			->orderBy(['claims_nickname.id'=> SORT_DESC]);
		if($limit) $claims = $claims->limit($limit);
		return $claims = $claims->all();
	}

}