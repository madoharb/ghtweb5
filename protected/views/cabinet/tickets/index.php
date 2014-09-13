<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var Tickets[] $data
 */

$title_ = Yii::t('main', 'Поддержка - список тикетов');
$this->pageTitle = $title_;
$this->breadcrumbs=array($title_);
?>

<?php echo CHtml::link(Yii::t('main', 'Создать тикет'), array('/cabinet/tickets/add')) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table">
    <thead>
        <tr>
            <th><?php echo Yii::t('main', 'Номер') ?></th>
            <th><?php echo Yii::t('main', 'Название') ?></th>
            <th><?php echo Yii::t('main', 'Категория') ?></th>
            <th><?php echo Yii::t('main', 'Статус') ?></th>
            <th><?php echo Yii::t('main', 'Дата создания') ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr<?php echo ($row->new_message_for_user == Tickets::STATUS_NEW_MESSAGE_ON && $row->isStatusOn() ? ' class="new-message"' : '') ?>>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo CHtml::link($row->title, array('/cabinet/tickets/view', 'ticket_id' => $row->id)) ?></td>
                    <td><?php echo e($row->category->title) ?></td>
                    <td><?php echo $row->getStatus() ?></td>
                    <td><?php echo $row->getDate() ?></td>
                    <td>
                        <ul class="list-unstyled">
                            <?php if($row->status == 1) { ?>
                                <li><?php echo CHtml::link('', array('/cabinet/tickets/close', 'ticket_id' => $row->id), array('class' => 'glyphicon glyphicon-eye-close', 'title' => Yii::t('main', 'Закрыть'), 'rel' => 'tooltip')) ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6"><?php echo Yii::t('main', 'Нет данных.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>