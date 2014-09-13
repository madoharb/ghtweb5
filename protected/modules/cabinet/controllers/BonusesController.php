<?php

class BonusesController extends CabinetBaseController
{
    public function actionIndex()
    {
        $bonuses = Bonuses::model()->opened()->findAll();
        $ids     = array();

        foreach($bonuses as $bonus)
        {
            $ids[] = $bonus->getPrimaryKey();
        }

        unset($bonuses);

        $dataProvider = new CActiveDataProvider('UserBonuses', array(
            'criteria' => array(
                'order' => 'created_at DESC',
                'condition' => 't.user_id = :user_id',
                'params' => array('user_id' => user()->getId()),
                'with' => array('bonusInfo' => array(
                    //'scopes' => array('opened'),
                    'with' => array('items' => array(
                        'scopes' => array('opened'),
                        'with' => 'itemInfo',
                    )),
                )),
            ),
            'pagination' => array(
                'pageSize' => config('cabinet.bonuses.limit'),
                'pageVar' => 'page',
            )
        ));

        $dataProvider->criteria->addInCondition('t.bonus_id', $ids);

        $this->render('//cabinet/bonuses/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionActivation($bonus_id)
    {
        if(request()->isPostRequest)
        {
            $char_id = (int) request()->getPost('char_id');

            $criteria = new CDbCriteria();

            $criteria->addCondition('user_id = :user_id AND t.id = :id');
            $criteria->params = array(':user_id' => user()->getId(), ':id' => $bonus_id);
            $criteria->with = array('bonusInfo' => array(
                'scopes' => array('opened'),
                'with' => array('items' => array(
                    'scopes' => array('opened'),
                    'with' => 'itemInfo',
                )),
            ));

            $bonus = UserBonuses::model()->find($criteria);

            if($bonus === NULL)
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Бонус не найден.'));
            }
            elseif($bonus->status == 1)
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Бонус уже активирован (дата активации: :date_activation).', array(':date_activation' => $bonus->updated_at)));
            }
            else
            {
                try
                {
                    $l2 = l2('gs', user()->gs_id)->connect();

                    $charIdfieldName = $l2->getField('characters.char_id');

                    $character = $l2->getDb()->createCommand("SELECT COUNT(0) FROM {{characters}} WHERE " . $charIdfieldName . " = :char_id")
                        ->bindParam(':char_id', $char_id, PDO::PARAM_INT)
                        ->queryScalar();

                    if(!$character)
                    {
                        user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Персонаж на сервере не найден.'));
                    }
                    else
                    {
                        // Предметы для записи в БД
                        $items = array();

                        foreach($bonus->bonusInfo->items as $item)
                        {
                            $items[] = array(
                                'owner_id' => $char_id,
                                'item_id'  => $item->item_id,
                                'count'    => $item->count,
                                'enchant'  => $item->enchant,
                            );
                        }

                        $res = $l2->multiInsertItem($items);

                        if($res > 0)
                        {
                            $bonus->status = 1;

                            $bonus->save(FALSE);

                            // Логирую действие юзера
                            if(app()->params['user_actions_log'])
                            {
                                $log = new UserActionsLog();

                                $log->user_id = user()->getId();
                                $log->action_id = UserActionsLog::ACTION_ACTIVATED_BONUS;
                                $log->params = json_encode($items);

                                $log->save(FALSE);
                            }

                            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Бонус активирован.'));
                        }
                        else
                        {
                            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Не удалось активировать Ваш бонус.'));
                        }
                    }
                }
                catch(Exception $e)
                {
                    user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__ . ' ' . __LINE__);
                }
            }
        }
        else
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Ошибка! Переданы не все параметры.'));
        }

        $this->redirectBack();
    }

    /**
     * Активация бонус кода
     */
    public function actionBonusCode()
    {
        $model = new BonusCodes('activated_code');

        if(isset($_POST['BonusCodes']))
        {
            $model->setAttributes($_POST['BonusCodes']);

            if($model->validate())
            {
                $criteria = new CDbCriteria(array(
                    'condition' => 'code = :code',
                    'params' => array(
                        ':code' => $model->code,
                    ),
                    'scopes' => array('opened'),
                    'with' => array('bonusInfo' => array(
                        'scopes' => array('opened'),
                    )),
                ));

                $bonus = BonusCodes::model()->find($criteria);

                if($bonus === NULL)
                {
                    $model->addError('code', Yii::t('main', 'Неверный код.'));
                }
                else
                {
                    $bonusId = $bonus->getPrimaryKey();
                    $userId  = user()->getId();

                    $bonusActivatedCount = db()->createCommand("SELECT COUNT(0) FROM {{bonus_codes_activated_logs}} WHERE code_id = :code_id")
                        ->bindParam('code_id', $bonusId, PDO::PARAM_INT)
                        ->queryScalar();

                    if($bonus->limit > 0 && ($bonusActivatedCount >= $bonus->limit))
                    {
                        $model->addError('code', Yii::t('main', 'Код не действителен.'));
                    }
                    elseif($bonus->bonusInfo->date_end != '' && (strtotime($bonus->bonusInfo->date_end) < time()))
                    {
                        $model->addError('code', Yii::t('main', 'Время действия кода закончено.'));
                    }
                    else
                    {
                        // Проверка чтобы юзер заюзал код только один раз
                        $user = db()->createCommand("SELECT created_at FROM {{bonus_codes_activated_logs}} WHERE code_id = :code_id AND user_id = :user_id LIMIT 1")
                            ->bindParam('code_id', $bonusId, PDO::PARAM_INT)
                            ->bindParam('user_id', $userId, PDO::PARAM_INT)
                            ->queryrow();

                        if($user)
                        {
                            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Вы уже активировали этот код (дата активации: :date_activation).', array(':date_activation' => $user['created_at'])));
                        }
                        else
                        {
                            $tr = db()->beginTransaction();

                            try
                            {
                                $userBonuses = new UserBonuses();

                                $userBonuses->bonus_id = $bonus->bonus_id;
                                $userBonuses->user_id = user()->getId();

                                $userBonuses->save(FALSE);

                                // Логирую активацию
                                $log = new BonusCodesActivatedLogs();

                                $log->code_id = $bonus->getPrimaryKey();
                                $log->user_id = user()->getId();

                                $log->save(FALSE);

                                $tr->commit();

                                // Логирую действие юзера
                                if(app()->params['user_actions_log'])
                                {
                                    $log = new UserActionsLog();

                                    $log->user_id = user()->getId();
                                    $log->action_id = UserActionsLog::ACTION_ACTIVATED_BONUS_CODE;
                                    $log->params = json_encode($bonus->getAttributes());

                                    $log->save(FALSE);
                                }

                                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Бонус код активирован.'));
                            }
                            catch(Exception $e)
                            {
                                $tr->rollback();
                                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                                Yii::log("Не удалось активировать бонус код\nBonus id: " . $bonusId . "\nError: " . $e->getMessage() . "\n", CLogger::LEVEL_ERROR, 'activation_bonus_code');
                            }
                        }

                        $this->refresh();
                    }
                }
            }
        }

        $this->render('//cabinet/bonuses/bonus-code', array(
            'model' => $model,
        ));
    }
}
