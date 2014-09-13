<?php

Yii::import('application.modules.cabinet.models.Tickets');
Yii::import('application.modules.cabinet.models.TicketsAnswers');
Yii::import('application.modules.cabinet.models.TicketsCategories');


class TicketsController extends BackendBaseController
{
	public function actionIndex()
	{
        $model = new Tickets();

        if(isset($_GET['Tickets']))
        {
            $model->setAttributes($_GET['Tickets']);
        }

        $dataProvider = $model->search();

		$this->render('//tickets/index', array(
            'dataProvider' => $dataProvider,
            'gs'           => CHtml::listData(Gs::model()->cache(60)->findAll(), 'id', 'name'),
            'categories'   => CHtml::listData(TicketsCategories::model()->cache(60)->findAll(), 'id', 'title'),
            'model'        => $model,
        ));
	}

    public function actionEdit($id)
    {
        $ticket = Tickets::model()->with(array('category', 'user'))->findByPk($id);

        if($ticket === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Тикет не найден.'));
            $this->redirect(array('/backend/tickets/index'));
        }

        if(!$ticket->user)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Связь с таблицей "Users" была нарушена.'));
            $this->redirect(array('/backend/tickets/index'));
        }

        // Убираю статус нового сообщения
        if($ticket->new_message_for_admin == Tickets::STATUS_NEW_MESSAGE_ON)
        {
            $ticket->new_message_for_admin = 0;
            $ticket->save(FALSE, array('new_message_for_admin', 'updated_at'));
        }

        // Ответы
        $answersDataProvider = new CActiveDataProvider('TicketsAnswers', array(
            'criteria' => array(
                'condition' => 'ticket_id = :ticket_id',
                'params' => array('ticket_id' => $ticket->id),
                'order' => 't.created_at DESC',
                'with' => 'userInfo'
            ),
            'pagination' => array(
                'pageSize' => 10,
                'pageVar'  => 'page',
            ),
        ));

        $model = new TicketsAnswers();

        if(isset($_POST['TicketsAnswers']))
        {
            $model->setAttributes($_POST['TicketsAnswers']);
            $model->ticket_id = $id;

            if($model->save())
            {
                // change new message status
                $ticket->new_message_for_user = 1;

                $ticket->save(FALSE, array('new_message_for_user', 'updated_at'));

                app()->notify->userNoticeTicketAnswer($ticket->user->email, array(
                    'ticket' => $ticket,
                    'user'   => $ticket->user,
                ));

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Ответ добавлен.'));
                $this->refresh();
            }
        }

        $this->render('//tickets/edit', array(
            'ticket'              => $ticket,
            'model'               => $model,
            'answersDataProvider' => $answersDataProvider,
        ));
    }

    public function actionCategories()
    {
        $dataProvider = new CActiveDataProvider('TicketsCategories', array(
            'criteria' => array(
                'order' => 'sort',
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

        $this->render('//tickets/categories', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCategoryForm($category_id = NULL)
    {
        if(is_numeric($category_id))
        {
            $model = $this->loadModel($category_id);
        }
        else
        {
            $model = new TicketsCategories();
        }

        if(isset($_POST['TicketsCategories']))
        {
            $model->setAttributes($_POST['TicketsCategories']);

            if($model->save())
            {
                $msg = 'Категория добавлена.';

                if(is_numeric($category_id))
                {
                    $msg = Yii::t('backend', 'Изменения сохранены.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//tickets/categoryForm', array(
            'model' => $model,
        ));
    }

    public function actionCategoryDel($category_id)
    {
        $model = $this->loadModel($category_id);

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Категория <b>:name</b> удалена, ID :id', array(':name' => e($model->title), ':id' => $category_id)));
        }

        $this->redirectBack();
    }

    public function actionCategoryAllow($category_id)
    {
        $model = $this->loadModel($category_id);

        $status = ($model->isStatusOn() ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function loadModel($id)
    {
        $model = TicketsCategories::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}