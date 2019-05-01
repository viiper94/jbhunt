<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

define('__FILEDIR__', $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/mods_mp/');
define('__IMGDIR__', $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/images/mods/');

class AddModForm extends Model{

    public $category;
    public $title;
    public $description;
    public $picture = 'mods/default.jpg';
    public $file;
    public $file_name;
    public $yandex_link;
    public $gdrive_link;
    public $mega_link;
    public $warning;
    public $trailer;
    public $dlc = array();

    public $tr_name;
    public $tr_image;

    public function __construct($id = null){
        if(isset($id)){
            $mod = Mods::find()
                ->select(['mods.*', 'trailers.name as tr_name', 'trailers.picture as tr_image'])
                ->leftJoin('trailers', 'trailers.id = mods.trailer')
                ->where(['mods.id' => $id])
                ->one();
            $this->category = implode('/', [$mod->game, $mod->category, $mod->subcategory]);
            $this->title = $mod->title;
            $this->description = $mod->description;
            $this->warning = $mod->warning;
            $this->picture = $mod->tr_name ? 'trailers/'.$mod->tr_image : 'mods/'.$mod->picture;
            $this->yandex_link = $mod->yandex_link;
            $this->gdrive_link = $mod->gdrive_link;
            $this->mega_link = $mod->mega_link;
            $this->trailer = $mod->trailer;
            $this->tr_name = $mod->tr_name;
            $this->file_name = $mod->file_name;
            $this->dlc = unserialize($mod->dlc);
        }
    }

    public function rules(){
        return [
            [['category', 'title'], 'required'],
            [['title', 'yandex_link', 'gdrive_link', 'mega_link', 'warning'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2048],
            [['trailer', 'dlc'], 'safe'],
			[['picture'], 'file', 'extensions' => 'jpg png', 'maxSize' => 16500000]
        ];
    }

    public function attributeLabels(){
        return [
            'category' => 'Категория',
            'title' => 'Название модификации',
            'description' => 'Описание модификации',
            'warning' => 'Предупреждение',
            'picture' => 'Изображение',
            'yandex_link' => 'Ссылка в Yandex.Диск',
            'gdrive_link' => 'Ссылка в Google Drive',
            'mega_link' => 'Ссылка в MEGA',
            'trailer' => 'Трейлер',
        ];
    }

    public function addMod(){
        $last_mod = Mods::find()->orderBy(['sort' => SORT_DESC])->one();
        $mod = new Mods();
        $category = explode('/', $this->category);
        $mod->game = $category[0];
        $mod->category = $category[1];
        $mod->subcategory = $category[2];
        $mod->title = $this->title;
        $mod->description = $this->description;
        $mod->warning = $this->warning;
        $mod->yandex_link = $this->yandex_link;
        $mod->gdrive_link = $this->gdrive_link;
        $mod->mega_link = $this->mega_link;
        $mod->dlc = serialize($this->dlc);
        $mod->trailer = $this->trailer == '0' ? null : $this->trailer;
        $mod->sort = ($last_mod ? intval($last_mod->sort) : 0)+1;
        if($file = UploadedFile::getInstance($this, 'file')){
            $mod->file_name = time().'_'.$this->transliterate($file->name);
            $file->saveAs(__FILEDIR__.$mod->game.'/'.$mod->file_name);
        }
		if($picture = UploadedFile::getInstance($this, 'picture')){
			if($picture->size > 1500000){
				$img = new Image();
				$img->load($picture->tempName);
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($picture->tempName);
			}
			$mod->picture = str_replace(['.png', '.jpg'], '', $picture->name).'_'.time().'.jpg';
			$picture->saveAs(__IMGDIR__.$mod->picture);
		}
		return $mod->save();
    }

    public function editMod($id){
        $mod = Mods::findOne($id);
        $category = explode('/', $this->category);
        $mod->game = $category[0];
        $mod->category = $category[1];
        $mod->subcategory = $category[2];
        $mod->title = $this->title;
        $mod->description = $this->description;
        $mod->warning = $this->warning;
        $mod->yandex_link = $this->yandex_link;
        $mod->gdrive_link = $this->gdrive_link;
        $mod->mega_link = $this->mega_link;
        $mod->dlc = serialize($this->dlc);
        $mod->trailer = $this->trailer == '0' ? null : $this->trailer;
        if($this->trailer != '0' && $mod->picture) {
            if(file_exists(__IMGDIR__.$mod->picture)){
                unlink(__IMGDIR__.$mod->picture);
            }
            $mod->picture = null;
        }
        if($file = UploadedFile::getInstance($this, 'file')){
			if(file_exists(__FILEDIR__.$mod->game.'/'.$mod->file_name)){
				unlink(__FILEDIR__.$mod->game.'/'.$mod->file_name);
			}
            $mod->file_name = time().'_'.$this->transliterate($file->name);
            $file->saveAs(__FILEDIR__.$mod->game.'/'.$mod->file_name);
        }
        if($picture = UploadedFile::getInstance($this, 'picture')){
			$mod->trailer = null;
            if($mod->picture !== 'default.jpg' && file_exists(__IMGDIR__.$mod->picture)){
                unlink(__IMGDIR__.$mod->picture);
            }
			if($picture->size > 1500000){
				$img = new Image();
				$img->load($picture->tempName);
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($picture->tempName);
			}
			$mod->picture = str_replace(['.png', '.jpg'], '', $picture->name).'_'.time().'.jpg';
			$picture->saveAs(__IMGDIR__.$mod->picture);
        }
        return $mod->update() !== false;
    }

    private function transliterate($str){
        $ru = ['а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

            ' ' => '_'];

        return strtr($str, $ru);

    }

}