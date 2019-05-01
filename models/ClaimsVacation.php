<?php

namespace app\models;

class ClaimsVacation extends Claims{

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
            [['member_id'], 'required'],
            [['member_id', 'viewed'], 'integer'],
            [['date', 'to_date'], 'safe'],
            [['reason'], 'string', 'max' => 512],
            [['status'], 'string', 'max' => 45],
            [['vacation_undefined'], 'integer'],
        ];
    }

	public static function getClaims($limit = null){
		$claims = ClaimsVacation::find()
			->select([
				'users.*',
				'users.id as uid',
				'claims_vacation.*',
				'admin.first_name as a_first_name',
				'admin.last_name as a_last_name',
				'vtc_members.id as v_member_id'
			])
			->innerJoin('users', 'users.id = claims_vacation.user_id')
			->leftJoin('users as admin', 'admin.id = claims_vacation.viewed')
			->leftJoin('vtc_members', 'vtc_members.id = claims_vacation.member_id')
			->orderBy(['claims_vacation.id'=> SORT_DESC]);
		if($limit) $claims = $claims->limit($limit);
		return $claims = $claims->all();
	}

}