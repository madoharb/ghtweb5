<?php
$title_ = Yii::t('backend', 'Магазин');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    Yii::t('backend', 'Сервера') => array('/backend/gameServers/index'),
    $gs->name . ' - ' . $title_
);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Создать категорию'), array('/backend/gameServers/shopCategoryForm', 'gs_id' => $gs->id), array('class' => 'btn btn-primary')) ?>

<table class="table">
    <thead>
        <tr>
            <th><?php echo Yii::t('backend', 'Название') ?></th>
            <th width="15%"><?php echo Yii::t('backend', 'Ссылка') ?></th>
            <th width="15%"><?php echo Yii::t('backend', 'Кол-во наборов') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Сортировка') ?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($categories) { ?>
            <?php foreach($categories as $category) { ?>
                <tr>
                    <td><?php echo e($category->name) ?></td>
                    <td><?php echo $category->link ?></td>
                    <td><?php echo $category->countPacks ?></td>
                    <td><span class="label <?php echo ($category->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $category->getStatus() ?></span></td>
                    <td><?php echo $category->sort ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryForm', 'gs_id' => $gs->id, 'category_id' => $category->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryAllow', 'gs_id' => $gs->id, 'category_id' => $category->id), array('class' => ($category->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($category->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryPacks', 'gs_id' => $gs->id, 'category_id' => $category->id), array('class' => 'glyphicon glyphicon-th', 'title' => Yii::t('backend', 'Наборы'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/gameServers/shopCategoryDel', 'gs_id' => $gs->id, 'category_id' => $category->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
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

<p style="font-size: 12px;">
    <span style="color: red;">*</span> Внимание!!!! при удалении категории также удаляются все наборы и все предметы в наборах!
</p>