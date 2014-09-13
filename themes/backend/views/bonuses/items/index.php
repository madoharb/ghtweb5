<?php
/**
 * @var BonusesController $this
 * @var Bonuses $bonus
 * @var CActiveDataProvider $dataProvider
 * @var BonusesItems[] $data
 */

$title_ = Yii::t('backend', 'Бонусы');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/bonuses/index'),
    $bonus->title,
);
?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Добавить предмет'), array('/backend/bonuses/itemAdd', 'bonus_id' => $bonus->id), array('class' => 'btn btn-primary')) ?>

<table class="table">
    <thead>
        <tr>
            <th width="5%"></th>
            <th><?php echo Yii::t('backend', 'Название') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Кол-во') ?></th>
            <th width="5%"><?php echo Yii::t('backend', 'Заточка') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="12%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->itemInfo->getIcon() ?></td>
                    <td><?php echo e($row->itemInfo->name) ?></td>
                    <td><?php echo number_format($row->count, 0, '', '.') ?></td>
                    <td><?php echo $row->enchant ?></td>
                    <td><?php echo $row->getStatus() ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/bonuses/itemEdit', 'bonus_id' => $bonus->id, 'item_id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/bonuses/itemAllow', 'bonus_id' => $bonus->id, 'item_id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/bonuses/itemDel', 'bonus_id' => $bonus->id, 'item_id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6"><?php echo Yii::t('backend', 'Нет данных') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>