<?php
$title__ = Yii::t('backend', 'Наборы');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    Yii::t('backend', 'Сервера') => array('/backend/gameServers/index'),
    $gs->name . ' - ' . Yii::t('backend', 'Магазин') => array('/backend/gameServers/shop', 'gs_id' => $gs->id),
    Yii::t('backend', 'Наборы для категории - :category_name', array(':category_name' => $category->name)) => array('/backend/gameServers/shopCategoryPacks', 'gs_id' => $gs->id, 'category_id' => $category->id),
    Yii::t('backend', 'Предметы в наборе - :pack_name', array(':pack_name' => $pack->title))
) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Добавить предмет'), array('/backend/gameServers/shopCategoryPackCreateItem', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id), array('class' => 'btn btn-primary')) ?>

<style>
.pack-img img
{
    width: 150px;
}
.desc h3
{
    margin-top: 0;
}
</style>

<table class="table">
    <thead>
        <tr>
            <th width="5%"></th>
            <th><?php echo Yii::t('backend', 'Название') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Стоимость') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Скидка') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Кол-во') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Заточка') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($items = $dataProvider->getData()) { ?>
            <?php foreach($items as $item) { ?>
                <tr>
                    <td><?php echo $item->itemInfo->getIcon() ?></td>
                    <td>
                        <?php echo e($item->itemInfo->name) . ' (' . $item->itemInfo->item_id . ')' ?>
                        <?php if($item->itemInfo->add_name) { ?>
                            (<?php echo $item->itemInfo->add_name ?>)
                        <?php } ?>
                    </td>
                    <td><?php echo $item->cost ?></td>
                    <td><?php echo $item->discount ?></td>
                    <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                    <td><?php echo $item->enchant ?></td>
                    <td><?php echo $item->getStatus() ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPackEditItem', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id, 'item_id' => $item->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPackDelItem', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id, 'item_id' => $item->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
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

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>