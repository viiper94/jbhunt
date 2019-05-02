<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class TrailersForm extends Model{

    public $id;
    public $name;
    public $picture;
    public $description;
    public $category;
    public $weight;
    public $game = 'ets';

    public function rules(){
        return [
            [['name'], 'required', 'message' => 'Введите название трейлера'],
            [['description', 'game', 'category', 'weight'], 'string'],
            [['picture'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 16500000],
        ];
    }

    public function __construct($id = null){
        if(isset($id)){
            $trailer = Trailers::findOne($id);
            $this->id = $trailer->id;
            $this->name = $trailer->name;
            $this->description = $trailer->description;
            $this->game = $trailer->game;
            $this->category = $trailer->category;
            $this->picture = $trailer->picture;
            $this->weight = $trailer->weight;
        }
    }

    public function addTrailer(){
        $trailer = new Trailers();
        $trailer->name = $this->name;
        $trailer->description = $this->description;
        $trailer->game = $this->game;
        $trailer->category = $this->category;
        $trailer->weight = $this->weight;
        if($trailer->save()){
            if($picture = UploadedFile::getInstance($this, 'picture')){
				if($picture->size > 1500000){
					$img = new Image();
					$img->load($picture->tempName);
					if($img->getWidth() > 1920){
						$img->resizeToWidth(1920);
					}
					$img->save($picture->tempName);
				}
				$trailer->picture = str_replace(['.png', '.jpg'], '', $picture->name).'_'.time().'.jpg';
                $picture->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/trailers/'.$trailer->picture);
                return $trailer->update() != false;
            }else{
                return true;
            }
        }
        return false;
    }

    public function editTrailer($id){
        $trailer = Trailers::findOne($id);
        $trailer->name = $this->name;
        $trailer->description = $this->description;
        $trailer->game = $this->game;
        $trailer->category = $this->category;
        $trailer->weight = $this->weight;
        if($picture = UploadedFile::getInstance($this, 'picture')){
			if($trailer->picture !== 'default.jpg' && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/trailers/'.$trailer->picture)){
				unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/trailers/'.$trailer->picture);
			}
        	if($picture->size > 1500000){
				$img = new Image();
				$img->load($picture->tempName);
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($picture->tempName);
			}
            $trailer->picture = str_replace(['.png', '.jpg'], '', $picture->name).'_'.time().'.jpg';
            $picture->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/trailers/'.$trailer->picture);
        }
        return $trailer->update() !== false;
    }

    public function attributeLabels(){
        return [
            'name' => 'Название трейлера',
            'description' => 'Описание',
            'picture' => 'Изображение',
            'game' => 'Игра',
            'category' => 'Категория',
            'weight' => 'Вес груза (тонн)',
        ];
    }

}