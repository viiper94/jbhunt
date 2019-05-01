<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AchievementsForm extends Model{

    public $title;
    public $description;
    public $image;
    public $related;
    public $progress = 1;
    public $scores = 0;
    public $visible = true;

    public function __construct($id = null){
        if(isset($id)){
            $achievement = Achievements::findOne($id);
            $this->title = $achievement->title;
            $this->description = $achievement->description;
            $this->visible = $achievement->visible == '1';
            $this->image = $achievement->image;
            $this->progress = $achievement->progress;
            $this->scores = $achievement->scores;
            $this->related = $achievement->related;
        }
    }

    public function rules() {
        return [
            [['title'], 'required', 'message' => 'Обязательное поле'],
            [['description'], 'string'],
            [['image'], 'file', 'extensions' => ['png', 'jpg']],
            [['visible'], 'boolean'],
            [['progress'], 'integer', 'min' => 1],
            [['related', 'scores'], 'integer']
        ];
    }

    public function attributeLabels(){
        return [
            'title' => 'Название*',
            'description' => 'Описание',
            'image' => 'Изображение',
            'progress' => 'Количество этапов',
            'related' => 'От какого достижения зависит',
            'scores' => 'Баллов за выполненое достижение',
        ];
    }

    public function addAchievement(){
        $last_achievement = Achievements::find()->orderBy(['sort' => SORT_DESC])->one();
        $achievement = new Achievements();
        $achievement->title = $this->title;
        $achievement->description = $this->description;
        $achievement->visible = $this->visible ? '1' : '0';
        $achievement->progress = $this->progress;
        $achievement->date = date('Y-m-d');
        $achievement->related = $this->related;
        $achievement->scores = $this->scores;
        $achievement->sort = ($last_achievement ? intval($last_achievement->sort) : 0)+1;
        if($achievement->save() == 1){
            if($file = UploadedFile::getInstance($this, 'image')){
				if($file->size > 1500000){
					$img = new Image();
					$img->load($file->tempName);
					if($img->getWidth() > 1024){
						$img->resizeToWidth(1024);
					}
					$img->save($file->tempName);
				}
                $achievement->image = str_replace(['.png', '.jpg'], '', $file->name).'_'.time().'.jpg';
                $file->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/images/achievements/' . $achievement->image);
                return $achievement->update() !== false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    public function editAchievement($id){
        $achievement = Achievements::findOne($id);
        $achievement->title = $this->title;
        $achievement->description = $this->description;
        $achievement->related = $this->related;
        $achievement->progress = $this->progress;
        $achievement->scores = $this->scores;
        $achievement->visible = $this->visible ? '1' : '0';
        if($file = UploadedFile::getInstance($this, 'image')){
			if($achievement->image !== 'default.jpg' && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/images/achievements/'.$achievement->image)){
				unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/images/achievements/'.$achievement->image);
			}
			if($file->size > 1500000){
				$img = new Image();
				$img->load($file->tempName);
				if($img->getWidth() > 1024){
					$img->resizeToWidth(1024);
				}
				$img->save($file->tempName);
			}
			$achievement->image = str_replace(['.png', '.jpg'], '', $file->name).'_'.time().'.jpg';
            $file->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/web/images/achievements/' . $achievement->image);
        }
        return $achievement->update() !== false;
    }

}