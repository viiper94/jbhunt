<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Mods extends ActiveRecord{

	public $tr_image;
	public $tr_name;

    public function rules(){
        return [
            [['category', 'subcategory', 'title'], 'required'],
            [['category', 'subcategory', 'title', 'file_name', 'yandex_link', 'gdrive_link', 'mega_link'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2048],
            [['dlc'], 'string', 'max' => 512],
            [['picture'], 'string', 'max' => 45],
            [['game'], 'string', 'max' => 3],
            [['trailer'], 'safe'],
        ];
    }

	public static function getModsPath($game = 'ets', $path = false){
    	if($path) return $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/mods_mp/' . ($game == 'ats' ? 'ats/' : 'ets/');
		return Yii::$app->request->baseUrl.'/mods_mp/' . ($game == 'ats' ? 'ats/' : 'ets/');
    }

    public static function visibleMod($id, $action){
        $mod = Mods::findOne($id);
        $mod->visible = $action == 'show' ? '1' : '0';
        return $mod->update() == 1 ? true : false;
    }

    public static function deleteMod($id){
        $mod = Mods::findOne($id);
        if(file_exists(self::getModsPath($mod->game, true).$mod->file_name)){
            unlink(self::getModsPath($mod->game, true).$mod->file_name);
        }
        if($mod->picture && $mod->picture !== 'default.jpg' && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/mods/'.$mod->picture)){
        	unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/mods/'.$mod->picture);
		}
        return $mod->delete();
    }

    public static function resortMod($id, $dir){
        $mod = Mods::findOne($id);
        $mod_2 = Mods::find()->where(['game' => $mod->game, 'category' => $mod->category, 'subcategory' => $mod->subcategory]);
        if($dir === 'up'){
            $mod_2 = $mod_2->andWhere(['>', 'sort', $mod->sort])->orderBy(['sort' => SORT_ASC]);
        }elseif($dir === 'down'){
            $mod_2 = $mod_2->andWhere(['<', 'sort', $mod->sort])->orderBy(['sort' => SORT_DESC]);
        }
        $mod_2 = $mod_2->one();
        if($mod_2 == null) return true;
        $modSort_2 = $mod_2->sort;
        $sortTmp = $modSort_2;
        $modSort_2 = $mod->sort;
        $mod->sort = $sortTmp;
        $mod_2->sort = $modSort_2;
        return $mod_2->update() == 1 && $mod->update() == 1 ? true : false;
    }

}
