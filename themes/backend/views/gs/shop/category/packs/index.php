<?php
/**
 * @var GameServersController $this
 * @var ShopItemsPacks[] $packs
 * @var ShopCategories $category
 * @var Gs $gs
 */

$title__ = Yii::t('backend', 'Наборы');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    Yii::t('backend', 'Сервера') => array('/backend/gameServers/index'),
    $gs->name . ' - ' . Yii::t('backend', 'Магазин') => array('/backend/gameServers/shop', 'gs_id' => $gs->id),
    Yii::t('backend', 'Наборы для категории - :category_name', array(':category_name' => $category->name))
) ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Создать набор'), array('/backend/gameServers/shopCategoryPacksForm', 'gs_id' => $gs->id, 'category_id' => $category->id), array('class' => 'btn btn-primary')) ?>

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
            <th width="10%"><?php echo Yii::t('backend', 'Картинка') ?></th>
            <th><?php echo Yii::t('backend', 'Название/Описание') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Кол-во предметов') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Сортировка') ?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($packs) { ?>
            <?php foreach($packs as $pack) { ?>
                <tr>
                    <td class="pack-img"><?php echo ($pack->imgIsExists() ? CHtml::image($pack->getImgUrl()) : '') ?></td>
                    <td class="desc">
                        <?php echo e($pack->title) ?><br>
                        <?php echo wordLimiter($pack->description, 20) ?>
                    </td>
                    <td><?php echo $pack->countItems ?></td>
                    <td><span class="label <?php echo ($pack->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $pack->getStatus() ?></span></td>
                    <td><?php echo $pack->sort ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPacksForm', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPackAllow', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id), array('class' => ($pack->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($pack->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPackItems', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id), array('class' => 'glyphicon glyphicon-th', 'title' => Yii::t('backend', 'Предметы'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPackDel', 'gs_id' => $gs->id, 'category_id' => $category->id, 'pack_id' => $pack->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
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