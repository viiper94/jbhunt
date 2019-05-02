<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Gallery extends ActiveRecord{

	public function rules(){
        return [
            [['image', 'image_original'], 'required'],
            [['size', 'uploaded_by', 'visible'], 'integer'],
            [['upload_time'], 'safe'],
            [['image', 'image_original'], 'string', 'max' => 512],
            [['dimensions'], 'string', 'max' => 11],
            [['description'], 'string', 'max' => 2048],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'image' => 'Image',
            'image_original' => 'Image Original',
            'size' => 'Size',
            'dimensions' => 'Dimensions',
            'uploaded_by' => 'Uploaded By',
            'upload_time' => 'Upload Time',
            'visible' => 'Visible',
            'description' => 'Description',
        ];
    }

	public static function addImageToGallery($image, $description = null, $uid = null){
		$photo = new Gallery();
		$photo->image_original = time().'_'.$image['name'];
		$photo->image = $photo->image_original;
		if(move_uploaded_file($image['tmp_name'], $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/' . $photo->image)){
			if($image['size'] > 1500000){
				$photo->image = 's-'.$photo->image;
				$img = new Image();
				$img->load($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/' . $photo->image_original);
				$photo->dimensions = $img->getWidth() .'x'. $img->getHeight();
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/' . $photo->image);
			}
			$photo->description = nl2br($description);
			$photo->size = $image['size'];
			$photo->uploaded_by = $uid;
			$photo->upload_time = date('Y-m-d H:i:s');
			$photo->visible = User::isAdmin() ? '1' : '0';
			$last_img = Gallery::find()->orderBy(['sort' => SORT_DESC])->one();
			$photo->sort = ($last_img ? intval($last_img->sort) : 0)+1;
			return $photo->save() ? Yii::$app->request->baseUrl . '/images/gallery/'. $photo->image : false;
		}else{
			return false;
		}
    }

	public static function removePhoto($id){
		$photo = Gallery::findOne($id);
		if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/'.$photo->image)){
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/'.$photo->image);
		}
		if(file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/'.$photo->image_original)){
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/gallery/'.$photo->image_original);
		}
		return $photo->delete();
    }

	public static function visiblePhoto($id, $action){
		$photo = Gallery::findOne($id);
		if($action == 'show') $photo->visible = '1';
		else if($action == 'hide') $photo->visible = '0';
		return $photo->update() !== false;
	}

	public static function resortPhoto($id, $dir){
		$photo = Gallery::findOne($id);
		$photo2_query = Gallery::find();
		if($dir === 'up'){
			$photo2_query = $photo2_query->andWhere(['>', 'sort', $photo->sort])->orderBy(['sort' => SORT_ASC]);
		}elseif($dir === 'down'){
			$photo2_query = $photo2_query->andWhere(['<', 'sort', $photo->sort])->orderBy(['sort' => SORT_DESC]);
		}
		$photo2 = $photo2_query->one();
		if($photo2 == null) return false;
		$sortTmp = $photo2->sort;
		$photo2->sort = $photo->sort;
		$photo->sort = $sortTmp;
		return $photo->update() == 1 && $photo2->update() == 1 ? true : false;
	}

}