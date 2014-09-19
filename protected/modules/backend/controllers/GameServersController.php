<?php

Yii::import('application.modules.cabinet.models.ShopCategories');
Yii::import('application.modules.cabinet.models.ShopItemsPacks');
Yii::import('application.modules.cabinet.models.ShopItems');

class GameServersController extends BackendBaseController
{
	public function actionIndex()
	{
        $model = new Gs('search');
        $model->unsetAttributes();

        if(isset($_GET['Gs']))
        {
            $model->setAttributes($_GET['Gs']);
        }

        $dataProvider = $model->search();

		$this->render('//gs/index', array(
            'dataProvider' => $dataProvider,
            'model'        => $model,
        ));
	}

    public function actionForm($gs_id = NULL)
    {
        if($gs_id === NULL)
        {
            $model = new Gs();
        }
        else
        {
            $model = $this->loadGsModel($gs_id);
        }

        if(isset($_POST['Gs']))
        {
            $model->setAttributes($_POST['Gs']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Изменения сохранены.');

                if($gs_id === NULL)
                {
                    $msg = Yii::t('backend', 'Сервер добавлен.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//gs/form', array(
            'model' => $model,
        ));
    }

    public function actionAllow($gs_id)
    {
        $model = $this->loadGsModel($gs_id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>.', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function actionDel($gs_id)
    {
        $model = $this->loadGsModel($gs_id);

        $model->status = ActiveRecord::STATUS_DELETED;

        if($model->save(TRUE))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Сервер <b>:name</b> удален', array(':name' => e($model->name))));
        }

        $this->redirectBack();
    }

    public function loadGsModel($id)
    {
        $model = Gs::model()->not_deleted()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    public function actionShop($gs_id)
    {
        $criteria = new CDbCriteria(array(
            'condition' => 'gs_id = :gs_id',
            'params' => array(':gs_id' => $gs_id),
            'order' => 'sort',
            'with' => array('countPacks'),
        ));

        $categories = ShopCategories::model()->findAll($criteria);

        $this->render('//gs/shop/category/index', array(
            'gs'         => Gs::model()->not_deleted()->findByPk($gs_id),
            'categories' => $categories,
        ));
    }

    /**
     * Создание/Редактирование категории
     *
     * @param int $gs_id
     * @param int $category_id
     */
    public function actionShopCategoryForm($gs_id, $category_id = NULL)
    {
        if($category_id === NULL)
        {
            $model = new ShopCategories();
        }
        else
        {
            $model = $this->loadShopCategoriesModel($category_id);
        }

        if(isset($_POST['ShopCategories']))
        {
            $model->setAttributes($_POST['ShopCategories']);
            $model->setAttribute('gs_id', $gs_id);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Изменения сохранены.');

                if($category_id === NULL)
                {
                    $msg = Yii::t('backend', 'Категория создана.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//gs/shop/category/form', array(
            'gs'    => Gs::model()->findByPk($gs_id),
            'model' => $model,
        ));
    }

    /**
     * Удаление категории
     *
     * @param int $gs_id
     * @param int $category_id
     */
    public function actionShopCategoryDel($gs_id, $category_id)
    {
        $model = $this->loadShopCategoriesModel($category_id);

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Категория, наборы и предметы в наборах были удалены.'));
        }
        else
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, $model->getErrors());
        }

        $this->redirect(array('shop', 'gs_id' => $gs_id));
    }

    public function actionShopCategoryAllow($gs_id, $category_id)
    {
        $model = $this->loadShopCategoriesModel($category_id);

        $model->status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);

        $model->save(FALSE);

        user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>.', array(':status' => $model->getStatus())));
        $this->redirect(array('shop', 'gs_id' => $gs_id));
    }

    public function loadShopCategoriesModel($id)
    {
        $model = ShopCategories::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Наборы в категории
     *
     * @param int $gs_id
     * @param int $category_id
     */
    public function actionShopCategoryPacks($gs_id, $category_id)
    {
        $packs = ShopItemsPacks::model()->with('countItems')->findAll('category_id = :category_id', array(':category_id' => $category_id));

        $this->render('//gs/shop/category/packs/index', array(
            'gs'       => Gs::model()->findByPk($gs_id),
            'category' => $this->loadShopCategoriesModel($category_id),
            'packs'    => $packs,
        ));
    }

    /**
     * Создание/Редактирование набора для категории
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     */
    public function actionShopCategoryPacksForm($gs_id, $category_id, $pack_id = NULL)
    {
        if($pack_id === NULL)
        {
            $model = new ShopItemsPacks();
        }
        else
        {
            $model = $this->loadShopItemsPacksModel($pack_id);
        }

        if(isset($_POST['ShopItemsPacks']))
        {
            $model->setAttributes($_POST['ShopItemsPacks']);
            $model->category_id = $category_id;

            if($model->save())
            {
                $msg = Yii::t('backend', 'Изменения сохранены.');

                if($pack_id === NULL)
                {
                    $msg = Yii::t('backend', 'Набор создан.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//gs/shop/category/packs/form', array(
            'gs'       => Gs::model()->findByPk($gs_id),
            'category' => $this->loadShopCategoriesModel($category_id),
            'model'    => $model,
        ));
    }

    /**
     * Редактирование набора для категории
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     */
    public function actionShopCategoryPackEdit($gs_id, $category_id, $pack_id)
    {
        $model = $this->loadShopItemsPacksModel($pack_id);

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->category_id = $category_id;

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Набор сохранен.'));
                $this->refresh();
            }
        }

        $this->render('//gs/shop/packForm', array(
            'gs'       => Gs::model()->findByPk($gs_id),
            'category' => $this->loadShopCategoriesModel($category_id),
            'model'    => $model,
        ));
    }

    public function actionShopCategoryPackAllow($gs_id, $category_id, $pack_id)
    {
        $model = $this->loadShopItemsPacksModel($pack_id);

        $model->status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);

        $model->save(FALSE);

        user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>.', array(':status' => $model->getStatus())));
        $this->redirect(array('shopCategoryPacks', 'gs_id' => $gs_id, 'category_id' => $category_id));
    }

    /**
     * Удаление картинки в наборе
     */
    public function actionShopCategoryPackDelImage($gs_id, $category_id, $pack_id)
    {
        if(!request()->isAjaxRequest)
        {
            die;
        }

        $model = $this->loadShopItemsPacksModel($pack_id);

        $model->img = NULL;

        if($model->save(FALSE))
        {
            // Удаляю картинку
            $model->deleteImage();

            $this->ajax['status'] = 'success';
        }
        else
        {
            $this->ajax['msg'] = $model->getErrors();
        }

        echo json_encode($this->ajax);
        app()->end();
    }

    /**
     * Удаление набора
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     */
    public function actionShopCategoryPackDel($gs_id, $category_id, $pack_id)
    {
        $pack = $this->loadShopItemsPacksModel($pack_id);

        if($pack->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Набор <b>:name</b> и предметы в наборе были удалены.', array(':name' => $pack->title)));
        }
        else
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, $pack->getErrors());
        }

        $this->redirect(array('/backend/gameServers/shopCategoryPacks', 'gs_id' => $gs_id, 'category_id' => $category_id));
    }

    /**
     * Редактирование набора для категории
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     */
    public function actionShopCategoryPackItems($gs_id, $category_id, $pack_id)
    {
        $dataProvider = new CActiveDataProvider('ShopItems', array(
            'criteria' => array(
                'condition' => 'pack_id = :pack_id',
                'params' => array(':pack_id' => $pack_id),
                'with' => array('itemInfo'),
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

        $this->render('//gs/shop/category/packs/packItems', array(
            'gs'           => Gs::model()->findByPk($gs_id),
            'category'     => $this->loadShopCategoriesModel($category_id),
            'pack'         => $this->loadShopItemsPacksModel($pack_id),
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Добавление предмета в набор
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     */
    public function actionShopCategoryPackCreateItem($gs_id, $category_id, $pack_id)
    {
        $model = new ShopItems();

        if(isset($_POST['ShopItems']))
        {
            $model->setAttributes($_POST['ShopItems']);
            $model->pack_id = $pack_id;

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Предмет добавлен в набор.'));
                $this->refresh();
            }
        }

        $this->render('//gs/shop/category/packs/itemForm', array(
            'gs'       => Gs::model()->findByPk($gs_id),
            'category' => $this->loadShopCategoriesModel($category_id),
            'pack'     => $this->loadShopItemsPacksModel($pack_id),
            'model'    => $model,
        ));
    }

    /**
     * Добавление предмета в набор
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     * @param int $item_id
     */
    public function actionShopCategoryPackEditItem($gs_id, $category_id, $pack_id, $item_id)
    {
        $model = ShopItems::model()->with('itemInfo')->findByPk($item_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Предмет не найден.'));
            $this->redirect(array('/backend/gameServers/shopCategoryPackItems', 'gs_id' => $gs_id, 'category_id' => $category_id, 'pack_id' => $pack_id));
        }

        $model->item_name = $model->itemInfo->name . ($model->itemInfo->add_name ? ' (' . $model->itemInfo->add_name . ')' : '');

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->pack_id = $pack_id;

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Изменения сохранены.'));
                $this->refresh();
            }
        }

        $this->render('//gs/shop/category/packs/itemForm', array(
            'gs'       => Gs::model()->findByPk($gs_id),
            'category' => $this->loadShopCategoriesModel($category_id),
            'pack'     => $this->loadShopItemsPacksModel($pack_id),
            'model'    => $model,
        ));
    }

    /**
     * Удаление предмета из набора
     *
     * @param int $gs_id
     * @param int $category_id
     * @param int $pack_id
     * @param int $item_id
     *
     * @throws CHttpException
     */
    public function actionShopCategoryPackDelItem($gs_id, $category_id, $pack_id, $item_id)
    {
        $model = ShopItems::model()->findByPk($item_id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Предмет из набора удалён.'));
        }
        else
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, $model->getErrors());
        }

        $this->redirect(array('shopCategoryPackItems', 'gs_id' => $gs_id, 'category_id' => $category_id, 'pack_id' => $pack_id));
    }

    public function loadShopItemsPacksModel($id)
    {
        $model = ShopItemsPacks::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
