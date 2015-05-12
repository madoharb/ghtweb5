<?php
/**
 * @var TransactionHistoryController $this
 * @var CActiveDataProvider $dataProvider
 * @var Transactions[] $data
 */

$title_ = Yii::t('main', 'История пополнений');
$this->pageTitle = $title_;

$this->breadcrumbs=array($title_);
?>

<div class="entry">
    <div class="scroll-pane">
        <table class="table">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th><?php echo $this->gs->getCurrencyName() ?></th>
                    <th><?php echo Yii::t('main', 'Стоимость') ?></th>
                    <th><?php echo Yii::t('main', 'Статус') ?></th>
                    <th><?php echo Yii::t('main', 'Тип') ?></th>
                    <th width="25%"><?php echo Yii::t('main', 'Дата') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($data = $dataProvider->getData()) { ?>
                    <?php foreach($data as $i => $transaction) { ?>
                        <tr>
                            <td><?php echo $transaction->getPrimaryKey() ?></td>
                            <td><?php echo $transaction->getCount() ?></td>
                            <td><?php echo $transaction->getSum() ?></td>
                            <td><?php echo $transaction->status
                                    ? '<span style="color: green;">' . Yii::t('main', 'Завершена') . '</span>'
                                    : '<span style="color: red;">' . Yii::t('main', 'Не завершена') . '</span>' ?></td>
                            <td><?php echo $transaction->getType() ?></td>
                            <td><?php echo $transaction->getDate() ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo Yii::t('main', 'Нет данных.') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>