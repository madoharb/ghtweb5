<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var Tickets[] $data
 * @var Tickets $model
 * @var array $gs
 * @var array $categories
 * @var CActiveForm $form
 */

$title_ = Yii::t('backend', 'Тикеты');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/index'),
)) ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo Yii::t('backend', 'Название') ?></th>
                <th width="14%"><?php echo Yii::t('backend', 'Категория') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Приоритет') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
                <th width="14%"><?php echo Yii::t('backend', 'Новые сообщения') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Сервер') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Автор') ?></th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'title', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'category_id', $categories, array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td><?php echo $form->dropDownList($model, 'priority', $model->getPrioritiesList(), array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control input-sm', 'empty' => '-- select --', 'options' => array('empty' => array('selected' => 'selected')))) ?></td>
                <td><?php echo $form->dropDownList($model, 'new_message_for_admin', array(Tickets::STATUS_NEW_MESSAGE_OFF => 'Нет', Tickets::STATUS_NEW_MESSAGE_ON => 'Да'), array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td><?php echo $form->dropDownList($model, 'gs_id', $gs, array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>" rel="tooltip"></button>
                    <?php echo HTML::link('', array('/backend/' . $this->getId() . '/index'), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'), 'rel' => 'tooltip')) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->getPrimaryKey() ?></td>
                        <td><?php echo CHtml::encode($row->title) ?></td>
                        <td><?php echo CHtml::encode($categories[$row->category_id]) ?></td>
                        <td><?php echo $row->getPriority() ?></td>
                        <td><span class="label <?php echo ($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                        <td><span class="label <?php echo ($row->new_message_for_admin == Tickets::STATUS_NEW_MESSAGE_ON ? 'label-info' : 'label-default') ?>"><?php echo $row->isNewMessageForAdmin() ?></span></td>
                        <td><?php echo CHtml::link(CHtml::encode($gs[$row->gs_id]), array('/backend/gameServers/form', 'gs_id' => $row->gs_id)) ?></td>
                        <td><?php echo (isset($row->user->login) ? CHtml::link($row->user->login, array('/backend/users/view', 'user_id' => $row->user->user_id)) : '*Unknown*') ?></td>
                        <td>
                            <ul class="actions list-unstyled">
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/edit', 'id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Просмотр'), 'rel' => 'tooltip')) ?></li>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
