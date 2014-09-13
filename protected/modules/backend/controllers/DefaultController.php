<?php

class DefaultController extends BackendBaseController
{
	public function actionIndex()
	{
		$this->render('//default/index');
	}

    /**
     * Поиск предметов в БД
     */
    public function actionGetItemInfo()
    {
        if(request()->getParam('item-id'))
        {
            $model = AllItems::model()->findByPk(request()->getParam('item-id'));

            if(!$model)
            {
                throw new CHttpException(404);
            }

            $this->ajax['status'] = 'success';
            $this->ajax['msg'] = $model->name . ($model->add_name ? ' (' . $model->add_name . ') [' . $model->item_id . ']' : '');

            echo CJSON::encode($this->ajax);
        }
        else
        {
            set_time_limit(0);

            $query = request()->getParam('query');
            $limit = request()->getParam('limit', 0);

            if(strlen($query))
            {
                $criteria = new CDbCriteria();
                $criteria->select = 'item_id, name, add_name, icon';

                if($limit > 0)
                {
                    $criteria->limit = $limit;
                }

                $criteria->order = 'name';
                $criteria->compare('name', $query, TRUE);

                $model = AllItems::model()->findAll($criteria);
                $items = array();

                foreach($model as $item)
                {
                    $items[] = array(
                        'id'    => $item->item_id,
                        'value' => $item->name . ($item->add_name ? ' (' . $item->add_name . ') [' . $item->item_id . ']' : ''),
                        'icon'  => $item->getIcon(),
                    );
                }

                echo CJSON::encode($items);
            }
        }

        app()->end();
    }

    /**
     * Обновление
     */
    public function actionUpdate()
    {
        $this->update();
    }

    /**
     * Обновление миграций
     */
    public function actionUpdateTables()
    {
        if(request()->isAjaxRequest)
        {
            echo $this->migrationInstall();
        }
    }

    /**
     * Очистка кэша
     */
    public function actionClearCache()
    {
        if(request()->isAjaxRequest)
        {
            cache()->flush();
            echo Yii::t('main', 'Кэш удален');
        }
    }
}