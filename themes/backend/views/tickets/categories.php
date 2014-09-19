<?php
/**
 * @var TicketsController $this
 * @var CActiveDataProvider $dataProvider
 * @var TicketsCategories[] $data
 */

$title_ = Yii::t('backend', 'Тикеты - категории');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Добавить новую категорию'), array('/backend/tickets/categoryForm'), array('class' => 'btn btn-primary')) ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th><?php echo Yii::t('backend', 'Название') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="14%"><?php echo Yii::t('backend', 'Сортировка') ?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo e($row->title) ?></td>
                    <td><span class="label <?php echo ($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                    <td><?php echo $row->sort ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/tickets/categoryForm', 'category_id' => $row->getPrimaryKey()), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/tickets/categoryAllow', 'category_id' => $row->getPrimaryKey()), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/tickets/categoryDel', 'category_id' => $row->getPrimaryKey()), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
