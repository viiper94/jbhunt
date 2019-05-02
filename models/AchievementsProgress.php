<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class AchievementsProgress extends ActiveRecord{

	public $title;
	public $description;
	public $image;
	public $progress;
	public $scores;
	public $u_company;
	public $u_nickname;
	public $member_id;

    public function rules(){
        return [
            [['ach_id', 'uid', 'proof'], 'required'],
            [['ach_id', 'uid', 'complete'], 'integer'],
            [['proof'], 'string', 'max' => 128],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'ach_id' => 'Ach ID',
            'uid' => 'Uid',
            'proof' => 'Progress',
        ];
    }

    public static function getAchievement($uid, $achid, $file){
        $ach = new AchievementsProgress();
        $ach->ach_id = $achid;
        $ach->uid = $uid;
        switch ($file['type']){
            case 'image/png': $ext = '.png'; break;
            case 'image/gif': $ext = '.gif'; break;
            case 'image/jpeg' :
            default: $ext = '.jpg';
        }
        $ach->proof = $uid.'-'.$achid.'-'.time().$ext;
        if(is_uploaded_file($file['tmp_name'])){
			if($file['size'] > 1500000){
				$img = new Image();
				$img->load($file['tmp_name']);
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($file['tmp_name']);
			}
			if(move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/achievements/progress/' . $ach->proof)){
				return $ach->save();
			}
		}
		return false;
    }

    public static function applyAchievement($id){
		$ach = AchievementsProgress::find()
			->select([
				'achievements_progress.id',
				'achievements_progress.ach_id',
				'achievements_progress.uid',
				'achievements_progress.complete',
				'achievements.progress',
				'achievements.scores'
			])
			->innerJoin('achievements', 'achievements_progress.ach_id = achievements.id')
			->where(['achievements_progress.id' => $id])->one();
        $ach->complete = 1;
        $user_progress = AchievementsProgress::find()->where(['uid' => $ach->uid, 'ach_id' => $ach->ach_id, 'complete' => 1])->count();
        $result = true;
        if(($ach->progress > 1 && $user_progress + 1 == $ach->progress) || $ach->progress == 1){
            $user = User::findOne($ach->uid);
            if($user->achievements == null){
                $user->achievements = serialize([$ach->ach_id]);
            }else{
                $achievements = unserialize($user->achievements);
                $achievements[] = $ach->ach_id;
                $user->achievements = serialize($achievements);
            }
            if($member = VtcMembers::findOne(['user_id' => $user->id])){
            	$scores = intval($ach->scores);
				$member->scores_other = intval($member->scores_other) + $scores;
				$member->scores_total = intval($member->scores_total) + $scores;
				$member->update();
			}
			Notifications::addNotification('Ваш скриншот для достижения прошел модерацию!', $user->id);
            $result = $user->update() !== false;
        }
        return $result && $ach->update(true, ['complete']) !== false;
    }

    public static function denyAchievement($id){
        $ach = AchievementsProgress::findOne($id);
		Notifications::addNotification('Ваш скриншот для достижения не прошел модерацию!', $ach->uid);
        if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/achievements/progress/'.$ach->proof)){
            unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/achievements/progress/'.$ach->proof);
        }
        return $ach->delete() !== false;
    }

	public static function deleteAchievement($id){
		$ach = AchievementsProgress::findOne($id);
		return $ach->delete();
    }

}