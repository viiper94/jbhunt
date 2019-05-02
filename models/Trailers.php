<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Trailers extends ActiveRecord{

	public $mod;

    public static function deleteTrailer($id){
        $trailer = Trailers::findOne($id);
        if($trailer->picture && $trailer->picture !== 'default.jpg'
			&& file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl).'/images/trailers/'.$trailer->picture){
            unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/trailers/'.$trailer->picture);
        }
        return $trailer->delete();
    }

    public static function getTrailers($append = array(), $game = null){
        $trailers_db = Trailers::find()->select(['id', 'name']);
        if($game != null) $trailers_db = $trailers_db->where(['game' => $game]);
        $trailers_db = $trailers_db->orderBy(['name' => SORT_ASC])->all();
        foreach ($append as $key => $value) {
            $trailers[$key] = $value;
        }
        foreach ($trailers_db as $trailer) {
            $trailers[$trailer->id] = $trailer->name;
        }
        return $trailers;
    }

    public static function getTrailersInfo($trailers){
        $query = Trailers::find()->select(['id', 'picture', 'name', 'description']);
        foreach ($trailers as $trailer){
            $query = $query->orWhere(['id' => $trailer]);
        }
        return $query->all();
    }

}