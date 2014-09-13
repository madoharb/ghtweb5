<?php

class TicketsController extends CabinetBaseController
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT COUNT(0), MAX(UNIX_TIMESTAMP(updated_at)) FROM {{tickets}} WHERE user_id = :user_id');
        $dependency->params = array('user_id' => user()->getId());
        $model = Tickets::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'user_id = :user_id',
                'params' => array(':user_id' => user()->getId()),
                'order' => 't.status DESC, t.created_at DESC',
                'with' => array(
                    'category' => array(
                        'scopes' => array('opened'),
                    ),
                ),
            ),
            'pagination' => array (
                'pageSize' => config('cabinet.tickets.limit'),
                'pageVar' => 'page',
            ),
        ));


        $this->render('//cabinet/tickets/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdd()
    {
        $model = new TicketsForm();
        $ticketModel = new Tickets();

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if($model->validate())
            {
                $transaction = db()->beginTransaction();

                try
                {
                    // Сохраняю тикет
                    $ticket = new Tickets();

                    $ticket->category_id           = $model->category_id;
                    $ticket->priority              = $model->priority;
                    $ticket->date_incident         = $model->date_incident;
                    $ticket->char_name             = $model->char_name;
                    $ticket->title                 = $model->title;
                    $ticket->new_message_for_admin = 1;
                    $ticket->gs_id                 = user()->getGsId();
                    $ticket->status                = Tickets::STATUS_ON;

                    $ticket->save(FALSE);

                    $ticketId = db()->getLastInsertID();

                    // Сохраняю переписку для тикета
                    $ticketAnswer = new TicketsAnswers();

                    $ticketAnswer->ticket_id = $ticketId;
                    $ticketAnswer->text = $model->text;

                    $ticketAnswer->save(FALSE);

                    // Логирую действие юзера
                    if(app()->params['user_actions_log'])
                    {
                        $log = new UserActionsLog();

                        $log->user_id = user()->getId();
                        $log->action_id = UserActionsLog::ACTION_CREATE_TICKET;

                        $log->save(FALSE);
                    }

                    app()->notify->adminNoticeTicketAdd(array(
                        'user'   => user()->getUser(),
                        'ticket' => $ticket,
                    ));

                    user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Тикет создан.'));

                    $transaction->commit();

                    $this->redirect(array('index'));
                }
                catch(Exception $e)
                {
                    $transaction->rollback();

                    user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__ . ' ' . __LINE__);
                }

                $this->refresh();
            }
        }

        $this->render('//cabinet/tickets/add', array(
            'model'       => $model,
            'ticketModel' => $ticketModel,
        ));
    }

    public function actionView($ticket_id)
    {
        $ticket = Tickets::model()->find('t.id = :id AND t.user_id = :user_id', array(':id' => $ticket_id, ':user_id' => user()->getId()));

        if($ticket === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Тикет не найден.'));
            $this->redirect(array('/cabinet/tickets/index'));
        }

        // Ответы
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(created_at)) FROM {{tickets_answers}} WHERE ticket_id = :ticket_id');
        $dependency->params = array('ticket_id' => $ticket_id);
        $model = TicketsAnswers::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'ticket_id = :ticket_id',
                'params' => array(':ticket_id' => $ticket->id),
                'order' => 't.created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => config('cabinet.tickets.answers.limit'),
                'pageVar' => 'page',
            ),
        ));

        $model = new TicketsAnswers();

        // При просмотре удаляю статус нового сообщения
        if($ticket->new_message_for_user)
        {
            $ticket->new_message_for_user = 0;
            $ticket->save(FALSE);
        }

        if(isset($_POST['TicketsAnswers']) && $ticket->isStatusOn())
        {
            $model->setAttributes($_POST['TicketsAnswers']);
            $model->setAttribute('ticket_id', $ticket_id);
            $model->setAttribute('gs_id', user()->getGsId());

            if($model->validate())
            {
                $model->save(FALSE);

                $ticket->new_message_for_admin = 1;
                $ticket->save(FALSE);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Ваше сообщение успешно добавлено!'));
                $this->refresh();
            }
        }

        $this->render('//cabinet/tickets/view', array(
            'ticket'              => $ticket,
            'model'               => $model,
            'answersDataProvider' => $dataProvider,
        ));
    }

    public function actionClose($ticket_id)
    {
        $ticket = Tickets::model()->findByPk($ticket_id);

        if($ticket === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Тикет не найден.'));
        }
        elseif($ticket->user_id != user()->getId())
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Можно закрыть только свой тикет.'));
        }
        elseif($ticket->isStatusOff())
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Тикет уже закрыт.'));
        }
        else
        {
            $ticket->status = ActiveRecord::STATUS_OFF;

            if($ticket->save(FALSE))
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Тикет :ticket_name закрыт.', array(':ticket_name' => '<b>' . e($ticket->title) . '</b>')));
            }
            else
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                Yii::log("Неудалось закрыть тикет\nID: " . $ticket_id . "\n", CLogger::LEVEL_ERROR, 'tickets');
            }
        }

        $this->redirectBack();
    }
}
