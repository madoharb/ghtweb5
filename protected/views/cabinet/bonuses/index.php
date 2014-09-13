<?php
/**
 * @var BonusesController $this
 * @var CActiveDataProvider $dataProvider
 * @var UserBonuses[] $bonuses
 */

$title_ = Yii::t('main', 'Мои бонусы');
$this->pageTitle = $title_;
$this->breadcrumbs=array($title_);
?>

<?php if($bonuses = $dataProvider->getData()) { ?>
    
    <?php $characters = user()->getCharacters() ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <ul class="list-unstyled bonus-items">
        <?php foreach($bonuses as $i => $bonus) { ?>
            <li>
                <h2><?php echo ++$i ?>) <?php echo e($bonus->bonusInfo->title) ?></h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="40"></th>
                            <th><?php echo Yii::t('main', 'Предмет') ?></th>
                            <th width="15%"><?php echo Yii::t('main', 'Кол-во') ?></th>
                            <th width="15%"><?php echo Yii::t('main', 'Заточка') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bonus->bonusInfo->items as $i2 => $item) { ?>
                            <tr>
                                <td><?php echo ++$i2 ?></td>
                                <td><?php echo Lineage::getItemIcon($item->itemInfo->icon, $item->itemInfo->name) ?></td>
                                <td><?php echo e($item->itemInfo->name) ?></td>
                                <td><?php echo number_format($item->count, 0, '', '.') ?></td>
                                <td><?php echo $item->enchant ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p class="calendar">
                    <?php if($bonus->isStatusOn()) { ?>
                        <?php echo Yii::t('main', 'Бонус активирован (дата активации: :date_activation).', array(':date_activation' => $bonus->getUpdatedAt())) ?>
                    <?php } else { ?>
                        <?php if($characters) { ?>
                            <?php echo CHtml::beginForm(array('activation', 'bonus_id' => $bonus->id), 'post', array('class' => 'form-inline')) ?>
                                <button type="submit" class="button">
                                    <span><?php echo Yii::t('main', 'Активировать на персонажа') ?></span>
                                </button>
                                <?php echo CHtml::dropDownList('char_id', '', CHtml::listData($characters, 'char_id', 'char_name'), array('class' => 'form-control')) ?>
                            <?php echo CHtml::endForm() ?>
                        <?php } else { ?>
                            <?php echo Yii::t('main', 'У Вас нет созданных персонажей, активация бонуса невозможна.') ?>
                        <?php } ?>
                    <?php } ?>
                </p>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <?php echo Yii::t('main', 'У Вас нет бонусов.') ?>
<?php } ?>