<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Appeals extends ActiveRecord{

    public $appealed_user_picture;
    public $appealed_user_nickname;
    public $appealed_user_company;
    public $from_user_nickname;
    public $from_user_company;
    public $from_user_first_name;
    public $from_user_last_name;

    public function rules(){
        return [
            [['is_anonymous', 'appeal_to_id', 'appeal_to_user_id', 'uid', 'viewed'], 'integer'],
            [['appeal_to_id'], 'required'],
            [['description', 'proof'], 'string', 'max' => 2048],
            [['date'], 'safe']
        ];
    }

    public static function removeAppeal($id){
        $appeal = Appeals::findOne($id);
        return $appeal->delete() !== false;
    }

    public static function viewedAppeal($id){
        $appeal = Appeals::findOne($id);
        $appeal->viewed = '1';
        return $appeal->update() !== false;
    }

}