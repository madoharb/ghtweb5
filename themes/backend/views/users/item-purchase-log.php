<?php
/**
 * @var UsersController $this
 * @var CActiveDataProvider $dataProvider
 * @var PurchaseItemsLog $model
 * @var PurchaseItemsLog[] $data
 * @var Users $user
 */

$title_ = Yii::t('backend', 'Юзеры');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    $title_ => array('/backend/users/index'),
    $user->login . ' - ' . Yii::t('backend', 'Просмотр покупок в магазине'),
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<table class="table">
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th><?php echo Yii::t('backend', 'Предмет') ?></th>
            <th width="7%"><?php echo Yii::t('backend', 'Сервер') ?></th>
            <th width="7%"><?php echo Yii::t('backend', 'Стоимость') ?></th>
            <th width="7%"><?php echo Yii::t('backend', 'Скидка') ?></th>
            <th width="7%"><?php echo Yii::t('backend', 'Кол-во') ?></th>
            <th width="7%"><?php echo Yii::t('backend', 'Заточка') ?></th>
            <th width="15%"><?php echo Yii::t('backend', 'ID персонажа на которого был перевод') ?></th>
            <th width="15%"><?php echo Yii::t('backend', 'Дата') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo $row->itemInfo->name ?> <?php echo ($row->itemInfo->add_name ? '(' . $row->itemInfo->add_name . ')' : '') ?></td>
                    <td><?php echo isset($row->gs->name) ? CHtml::link(e($row->gs->name), array('/backend/gameServers/form', 'gs_id' => $row->gs->getPrimaryKey())) : 'n/a' ?></td>
                    <td><?php echo $row->cost ?></td>
                    <td><?php echo $row->discount ?></td>
                    <td><?php echo $row->count ?></td>
                    <td><?php echo $row->enchant ?></td>
                    <td><?php echo $row->char_id ?></td>
                    <td><?php echo $row->getCreatedAt() ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="8"><?php echo Yii::t('backend', 'Нет данных') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
