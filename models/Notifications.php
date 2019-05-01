<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Notifications extends ActiveRecord{

    public function rules(){
        return [
            [['uid'], 'required'],
            [['uid', 'status'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string', 'max' => 512],
        ];
    }

    public static function addNotification($text, $uid){
        $notification = new Notifications();
        $notification->date = date('Y-m-d H:i');
        $notification->text = htmlentities($text);
        $notification->uid = $uid;
        return $notification->save();
    }

    public static function addNotificationsToMembers($text){
        $members = VtcMembers::find()->all();
        foreach($members as $member){
            $notification = new Notifications();
            $notification->date = date('Y-m-d H:i');
            $notification->text = htmlentities($text);
            $notification->uid = $member->user_id;
            $notification->save();
        }
        return true;
    }

    public static function deleteNotification($id){
        $notification = Notifications::findOne([$id]);
        return $notification->delete() != false;
    }

    public static function markNotification($id){
        $notification = Notifications::findOne([$id]);
        $notification->status = '1';
        return $notification->save();
    }

}