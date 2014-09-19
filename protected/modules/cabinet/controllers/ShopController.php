<?php

class ShopController extends CabinetBaseController
{
	public function actionIndex()
	{
        $this->render('//cabinet/shop/index', array(
            'categories' => $this->getCategories(),
        ));
	}

    public function getCategories()
    {
        $gs_id = user()->gs_id;

        $dependency = new CDbCacheDependency("SELECT COUNT(0), SUM(UNIX_TIMESTAMP(updated_at)) FROM {{shop_categories}} WHERE gs_id = :gs_id AND status = :status");
        $dependency->params = array('gs_id' => $gs_id, 'status' => ActiveRecord::STATUS_ON);

        $res = ShopCategories::model()->cache(3600 * 24, $dependency)->opened()->findAll('gs_id = :gs_id', array(':gs_id' => user()->gs_id));

        $categories = array();

        foreach($res as $row)
        {
            $categories[$row['id']] = $row;
        }

        return $categories;
    }

    /**
     * Предметы в категории
     *
     * @param string $category_link
     * @throws CHttpException
     */
    public function actionCategory($category_link)
    {
        $criteria = new CDbCriteria(array(
            'condition' => 'link = :link AND gs_id = :gs_id',
            'params' => array(
                'link'  => $category_link,
                'gs_id' => user()->getGsId(),
            ),
            'scopes' => array('opened'),
        ));

        $categoryModel = ShopCategories::model()->find($criteria);

        if(!$categoryModel)
        {
            throw new CHttpException(404, Yii::t('main', 'Нет данных.'));
        }


        // Наборы и предметы в наборах
        $dataProvider = new CActiveDataProvider('ShopItemsPacks', array(
            'criteria' => new CDbCriteria(array(
                'condition' => 'category_id = :category_id',
                'params'    => array(
                    'category_id' => $categoryModel->getPrimaryKey(),
                ),
                'scopes' => array('opened'),
                'order' => 't.sort',
                'with' => array('items' => array(
                    'scopes' => array('opened'),
                    'order' => 'items.sort',
                    'with' => array('itemInfo'),
                )),
            )),
            'pagination' => array(
                'pageVar'  => 'page',
                'pageSize' => 5,
            ),
        ));


        $this->render('//cabinet/shop/category', array(
            'categories'    => $this->getCategories(),
            'categoryModel' => $categoryModel,
            'dataProvider'  => $dataProvider,
        ));
    }

    /**
     * Покупка предметов
     *
     * @param string $category_link
     *
     * @return void
     */
    public function actionBuy($category_link)
    {
        if(!request()->getIsPostRequest() || !isset($_POST['pack_id']) || !is_numeric($_POST['pack_id']))
        {
            $this->redirect(array('index'));
        }

        // Предметы не выбраны
        if(!isset($_POST['items']) || !is_array($_POST['items']))
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Выберите предметы.'));
            $this->redirectBack();
        }

        // Не выбран персонаж
        if(!isset($_POST['char_id']) || !is_numeric($_POST['char_id']))
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Выберите персонажа.'));
            $this->redirectBack();
        }

        $_POST['items'] = array_map('intval', $_POST['items']);

        $char_id = (int) $_POST['char_id'];
        $packId  = (int) $_POST['pack_id'];
        $items   = $_POST['items'];

        // Проверяю есть ли такой раздел
        $category = array();

        foreach($this->getCategories() as $row)
        {
            if($row->link == $category_link)
            {
                $category = $row;
                break;
            }
        }

        // Пытаюстся купить в закрытой/несуществующей категории
        if(!$category)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Покупка невозможна.'));
            $this->redirectBack();
        }

        // Проверяю есть ли такой набор
        $criteria = new CDbCriteria(array(
            'condition' => 'id = :id AND category_id = :category_id',
            'params'    => array(
                'id'          => $packId,
                'category_id' => $category->getPrimaryKey(),
            ),
            'scopes' => array('opened'),
        ));

        $pack = ShopItemsPacks::model()->find($criteria);

        // Набор не найден
        if(!$pack)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Покупка невозможна.'));
            $this->redirectBack();
        }

        // Ищю предметы в наборе
        $criteria = new CDbCriteria(array(
            'condition' => 'pack_id = :pack_id',
            'params'    => array(
                'pack_id' => $pack->getPrimaryKey(),
            ),
            'scopes' => array('opened'),
            'with'   => array('itemInfo')
        ));

        $criteria->addInCondition('id', $items);

        $items = ShopItems::model()->findAll($criteria);

        // Если предметы не найдены
        if(!$items)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Покупка невозможна.'));
            $this->redirectBack();
        }


        // Сумма за донат
        $totalSumDonat = 0;

        // Сумма за голосования
        $totalSumVote  = 0;

        // Подсчитываю что почём
        foreach($items as $item)
        {
            if($item->currency_type == 'donat')
            {
                $totalSumDonat += ShopItems::costAtDiscount($item->cost, $item->discount);
            }
            elseif($item->currency_type == 'vote')
            {
                $totalSumVote += ShopItems::costAtDiscount($item->cost, $item->discount);
            }
        }

        // Проверка баланса
        if($totalSumDonat > 0 && user()->get('balance') < $totalSumDonat)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'У Вас недостаточно средств на балансе для совершения сделки.'));
            $this->redirectBack();
        }

        // Проверка баланса
        if($totalSumVote > 0 && user()->vote_balance < $totalSumVote)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'У Вас недостаточно Голосов для совершения сделки.'));
            $this->redirectBack();
        }


        // Смотрю персонажа на сервере
        try
        {
            $l2 = l2('gs', user()->getGsId())->connect();

            $charIdFieldName = $l2->getField('characters.char_id');
            $login           = user()->getLogin();

            $character = $l2->getDb()->createCommand("SELECT online FROM {{characters}} WHERE account_name = :account_name AND " . $charIdFieldName . " = :char_id LIMIT 1")
                ->bindParam('account_name', $login, PDO::PARAM_STR)
                ->bindParam('char_id', $char_id, PDO::PARAM_INT)
                ->queryRow();

            if(!$character)
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Персонаж на сервере не найден.'));
                $this->redirectBack();
            }

            if($character['online'] != 0)
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Персонаж НЕ должен находится в игре.'));
                $this->redirectBack();
            }

            // Подготавливаю предметы для БД
            $itemsToDb = array();

            foreach($items as $item)
            {
                $itemsToDb[] = array(
                    'owner_id' => $char_id,
                    'item_id'  => $item->item_id,
                    'count'    => $item->count,
                    'enchant'  => $item->enchant,
                );
            }


            // Накидываю предмет(ы) в игру
            $res = $l2->multiInsertItem($itemsToDb);

            if($res)
            {
                $userId = user()->getId();

                if($totalSumDonat > 0)
                {
                    db()->createCommand("UPDATE {{user_profiles}} SET balance = balance - :total_sum WHERE user_id = :user_id LIMIT 1")
                        ->bindParam('total_sum', $totalSumDonat)
                        ->bindParam('user_id', $userId, PDO::PARAM_INT)
                        ->execute();
                }

                if($totalSumVote)
                {
                    db()->createCommand("UPDATE {{user_profiles}} SET vote_balance = vote_balance - :total_sum WHERE user_id = :user_id LIMIT 1")
                        ->bindParam('total_sum', $totalSumVote)
                        ->bindParam('user_id', $userId, PDO::PARAM_INT)
                        ->execute();
                }

                // Записываю лог о сделке
                $itemsLog = array();
                $itemList = '';

                foreach($items as $i => $item)
                {
                    $itemList .= ++$i . ') ' . $item->itemInfo->getFullName() . ' x' . $item->count . '<br>';

                    $itemsLog[] = array(
                        'pack_id'       => $item->pack_id,
                        'item_id'       => $item->item_id,
                        'description'   => $item->description,
                        'cost'          => $item->cost,
                        'discount'      => $item->discount,
                        'currency_type' => $item->currency_type,
                        'count'         => $item->count,
                        'enchant'       => $item->enchant,
                        'user_id'       => user()->getId(),
                        'char_id'       => $char_id,
                        'gs_id'         => user()->getGsId(),
                        'created_at'    => date('Y-m-d H:i:s'),
                    );
                }

                if($itemsLog)
                {
                    $builder = db()->schema->commandBuilder;
                    $builder->createMultipleInsertCommand('{{purchase_items_log}}', $itemsLog)->execute();
                }

                // Логирую действие юзера
                if(app()->params['user_actions_log'])
                {
                    $log = new UserActionsLog();

                    $log->user_id   = user()->getId();
                    $log->action_id = UserActionsLog::ACTION_DEPOSIT_SUCCESS;
                    $log->params    = json_encode($itemsLog);

                    $log->save(FALSE);
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Сделка прошла успешно, Нижеперечисленные предметы в ближайшее время будут зачислены на Вашего персонажа.<br><b>:item_list</b>',
                    array(':item_list' => $itemList)));

                app()->notify->shopBuyItems(user()->get('email'), array(
                    'items' => $items,
                ));

                $this->redirectBack();
            }
        }
        catch(Exception $e)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'shop_buy');
            $this->redirectBack();
        }
    }
}