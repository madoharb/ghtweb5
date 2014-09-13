<?php
/**
 * @var ShopItems[] $items
 */

$totalSum = 0;
?>

<font color="#ead255" face="Trebuchet MS" style="font-size: 24px;"><?php echo Yii::t('main', 'Здравствуйте!') ?></font><br><br><br><br>
<?php echo Yii::t('main', 'Вы только что совершили покупку в нашем магазине.') ?><br><br>

<?php if($items) { ?>
    <table style="color: #FFFFFF;" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo Yii::t('main', 'Название') ?></th>
                <th><?php echo Yii::t('main', 'Кол-во') ?></th>
                <th><?php echo Yii::t('main', 'Заточка') ?></th>
                <th><?php echo Yii::t('main', 'Скидка') ?></th>
                <th><?php echo Yii::t('main', 'Цена') ?></th>
                <th><?php echo Yii::t('main', 'Цена со скидкой') ?></th>
            </tr>
        </thead>
        <?php foreach($items as $i => $item) { ?>
            <?php $totalSum += ShopItems::costAtDiscount($item->getCost(), $item->discount) ?>
            <tr>
                <td><?php echo ++$i ?></td>
                <td><?php echo e($item->itemInfo->getFullName()) ?></td>
                <td><?php echo $item->count ?></td>
                <td><?php echo $item->enchant ?></td>
                <td><?php echo $item->discount ?>%</td>
                <td><?php echo formatCurrency($item->getCost(), FALSE) ?></td>
                <td><?php echo formatCurrency(ShopItems::costAtDiscount($item->getCost(), $item->discount), FALSE) ?></td>
            </tr>
        <?php } ?>
        <tfoot>
            <td colspan="7"><?php echo Yii::t('main', 'Итого') ?>: <?php echo formatCurrency($totalSum) ?></td>
        </tfoot>
    </table>
<?php } ?>

<br>
<?php echo Yii::t('main', 'Спасибо за Вашу помощь в развитии проекта.') ?>