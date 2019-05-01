<?php

namespace app\models;

class ClaimsFired extends Claims{

	public $v_member_id;

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
            [['member_id'], 'required'],
            [['member_id', 'viewed'], 'integer'],
            [['date'], 'safe'],
            [['status'], 'string', 'max' => 45],
            [['reason'], 'string', 'max' => 2048],
        ];
    }

	public static function getClaims($limit = null){
		$claims = ClaimsFired::find()
			->select([
				'users.*',
				'users.id as uid',
				'claims_fired.*',
				'admin.first_name as a_first_name',
				'admin.last_name as a_last_name',
				'vtc_members.id as v_member_id'
			])
			->innerJoin('users', 'users.id = claims_fired.user_id')
			->leftJoin('users as admin', 'admin.id = claims_fired.viewed')
			->leftJoin('vtc_members', 'vtc_members.id = claims_fired.member_id')
			->orderBy(['claims_fired.id'=> SORT_DESC]);
		if($limit) $claims = $claims->limit($limit);
		return $claims = $claims->all();
	}

}