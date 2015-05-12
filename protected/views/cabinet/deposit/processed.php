<?php
/**
 * @var DepositController $this
 * @var Transactions $model
 * @var string $formAction
 * @var string $fields
 * @var Deposit $deposit
 */

$title_ = Yii::t('main', 'Подтверждение платежа');
$this->pageTitle = $title_;

$this->breadcrumbs = array(
    Yii::t('main', 'Пополнение баланса') => array('/cabinet/deposit/index'),
    $title_
);
?>

<table class="table table-striped">
    <tbody>
        <tr>
            <td><?php echo Yii::t('main', 'Платежная система') ?></td>
            <td><?php echo $deposit->getAggregatorName() ?></td>
        </tr>
        <tr class="even">
            <td width="40%"><?php echo Yii::t('main', 'Номер заявки') ?></td>
            <td width="60%"><?php echo $model->id ?></td>
        </tr>
        <tr>
            <td><?php echo Yii::t('main', 'Получаете') ?></td>
            <td><?php echo $model->count . ' ' . $this->gs->currency_name ?></td>
        </tr>
        <tr class="even">
            <td><?php echo Yii::t('main', 'Отдаете') ?></td>
            <td><?php echo app()->numberFormatter->formatCurrency($model->sum, $this->gs->currency_symbol) ?></td>
        </tr>
    </tbody>
</table>

<form action="<?php echo $formAction ?>" method="post">
    <?php echo $fields ?>
    <div class="button-group center">
        <button type="submit" class="button">
            <span><?php echo Yii::t('main', 'Перейти к оплате') ?></span>
        </button>
        <?php echo HTML::link(Yii::t('main', 'назад'), array('/cabinet/deposit/index')) ?>
    </div>
</form>