<?php
/**
 * @var BonusesController $this
 * @var CActiveDataProvider $dataProvider
 * @var UserBonuses[] $data
 */

$title_ = Yii::t('backend', 'Бонусы');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Создать'), array('/backend/' . $this->getId() . '/add'), array('class' => 'btn btn-primary')) ?>

<table class="table">
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th><?php echo Yii::t('backend', 'Название') ?></th>
            <th width="15%"><?php echo Yii::t('backend', 'Кол-во предметов') ?></th>
            <th width="20%"><?php echo Yii::t('backend', 'Дата окончания') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="12%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo e($row->title) ?></td>
                    <td><?php echo $row->itemCount ?></td>
                    <td><?php echo $row->getDateEnd() ?></td>
                    <td><?php echo $row->getStatus() ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/form', 'id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/items', 'bonus_id' => $row->id), array('class' => 'glyphicon glyphicon-th', 'title' => Yii::t('backend', 'Предметы для бонуса'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/allow', 'id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/del', 'id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
