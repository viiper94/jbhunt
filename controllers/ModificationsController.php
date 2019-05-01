<?php

namespace app\controllers;

use app\models\AddModForm;
use app\models\ModsCategories;
use app\models\ModsSubcategories;
use app\models\Trailers;
use app\models\User;
use Yii;
use app\models\Mods;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class ModificationsController extends Controller{

	public function behaviors(){
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['add', 'edit'],
				'rules' => [
					[
						'actions' => ['add', 'edit'],
						'allow' => false,
						'roles' => ['?']
					],
					[
						'actions' => ['add', 'edit'],
						'allow' => true,
						'roles' => ['@']
					],
				],
			],
		];
	}

    public function actionIndex(){
    	$game = Yii::$app->request->get('game', null);
    	$category = Yii::$app->request->get('category', null);
    	$subcategory = Yii::$app->request->get('subcategory', null);
    	$order_by = 'id';
		$mods = Mods::find()
			->select(['mods.*', 'trailers.picture as tr_image'])
			->leftJoin('trailers', 'mods.trailer = trailers.id');
		if(Yii::$app->request->get('q')){
			$mods = $mods->andWhere(['like', 'mods.title', Yii::$app->request->get('q')])
				->orWhere(['like', 'mods.description', Yii::$app->request->get('q')]);
		}
		if(!User::isAdmin()) $mods = $mods->where(['mods.visible' => '1']);
		if($game){
			$mods = $mods->andWhere(['mods.game' => $game]);
			if($category){
				$mods = $mods->andWhere(['mods.category' => $category]);
				if($subcategory){
					$mods = $mods->andWhere(['mods.subcategory' => $subcategory]);
					$order_by = 'sort';
				}else $subcategory = $category;
			}
		}
		$total = $mods->count();
		$pagination = new Pagination([
			'defaultPageSize' => 9,
			'totalCount' => $total
		]);
		$mods = $mods->orderBy(['mods.'.$order_by => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
		if($category){
			$subcategory = ModsSubcategories::find()
				->select([
					'mods_subcategories.*',
					'mods_categories.id as cat_id',
					'mods_categories.name as cat_name',
					'mods_categories.title as cat_title',
					'mods_categories.picture as cat_image',
				])
				->leftJoin('mods_categories', 'mods_categories.id = mods_subcategories.category_id')
				->where([
					'mods_subcategories.name' => $subcategory,
					'mods_categories.name' => $category,
					'mods_subcategories.for_ets' => $game == 'ets' ? '1' : '0'
				])
				->one();
		}else if($game){
			$subcategory = new ModsSubcategories();
			$subcategory->cat_name = $game;
			$subcategory->cat_title = 'Моды для ' . ($game == 'ets' ? 'ETS2MP' : 'ATSMP');
			$subcategory->cat_image = $game == 'ets' ? 'mods-main.jpg' : '2.jpg';
			$subcategory->for_ets = $game == 'ets' ? 1 : 0;
		}else{
			$subcategory = new ModsSubcategories();
			$subcategory->cat_name = 'all';
			$subcategory->cat_title = 'Модификации для TruckersMP';
			$subcategory->cat_image = 'mods-main.jpg';
		}

		$all_subcategories = ModsSubcategories::find()
			->select([
				'mods_subcategories.*',
				'mods_categories.name as cat_name',
				'mods_categories.title as cat_title'
			])
			->leftJoin('mods_categories', 'mods_categories.id = mods_subcategories.category_id')
			->orderBy(['for_ets' => SORT_DESC, 'category_id' => SORT_ASC])
			->asArray()->all();
		if(!$subcategory) return $this->redirect(['modifications/index']);
		return $this->render('index', [
			'mods' => $mods,
			'subcategory' => $subcategory,
			'all_subcategories' => ArrayHelper::index($all_subcategories, null, 'for_ets'),
			'currentPage' => Yii::$app->request->get('page', 1),
			'totalPages' => $pagination->getPageCount(),
			'pagination' => $pagination,
			'total' => $total,
		]);
    }

	public function actionTedit(){
		return $this->render('tedit');
	}

    public function actionAdd(){
        if(User::isAdmin()){
            $model = new AddModForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->addMod() != false){
                    $cat = explode('/', $model->category);
                    return $this->redirect(['modifications/index', 'game' => $cat[0], 'category' => $cat[1], 'subcategory' => $cat[2]]);
                }
            }
            return $this->render('edit', [
                'model' => $model,
                'categories' => ArrayHelper::merge(['Нет категории' => ['' => 'Выберите категорию']], ModsCategories::getCatsWithSubCats()),
                'trailers' => Trailers::getTrailers(['0' => 'Нет прицепа']),
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionEdit(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            $model = new AddModForm(Yii::$app->request->get('id'));
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->editMod(Yii::$app->request->get('id')) != false){
                    Mods::findOne(Yii::$app->request->get('id'));
                    return $this->goBack();
                }
            }
            Url::remember(Yii::$app->request->referrer);
            return $this->render('edit', [
                'model' => $model,
                'categories' => ModsCategories::getCatsWithSubCats(),
                'trailers' => Trailers::getTrailers(['0' => 'Нет прицепа'])
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionRemove(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            $mod = Mods::findOne(Yii::$app->request->get('id'));
            Mods::deleteMod(Yii::$app->request->get('id'));
            return $this->redirect(['modifications/index',
                'game' => $mod->game,
                'category' => $mod->category,
                'subcategory' => $mod->subcategory
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionSort(){
        if(Yii::$app->request->get('dir') && Yii::$app->request->get('id') && User::isAdmin()){
            $mod = Mods::findOne(Yii::$app->request->get('id'));
            Mods::resortMod(Yii::$app->request->get('id'), Yii::$app->request->get('dir'));
            return $this->redirect(['modifications/index', 'game' => $mod->game, 'category' => $mod->category, 'subcategory' => $mod->subcategory]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionShow(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            Mods::visibleMod(Yii::$app->request->get('id'), 'show');
            $mod = Mods::findOne(Yii::$app->request->get('id'));
            return $this->redirect(['modifications/index', 'game' => $mod->game, 'category' => $mod->category, 'subcategory' => $mod->subcategory]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionHide(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            Mods::visibleMod(Yii::$app->request->get('id'), 'hide');
            $mod = Mods::findOne(Yii::$app->request->get('id'));
            return $this->redirect(['modifications/index', 'game' => $mod->game, 'category' => $mod->category, 'subcategory' => $mod->subcategory]);
        }else{
            return $this->render('//site/error');
        }
    }

}