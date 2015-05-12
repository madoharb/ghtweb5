<?php
/**
 * @var DepositController $this
 * @var DepositForm $model
 * @var Deposit $deposit
 * @var ActiveForm $form
 */

$assetsUrl = assetsUrl();

$title_ = Yii::t('main', 'Пополнение баланса');
$this->pageTitle = $title_;

$this->breadcrumbs = array($title_);
?>

<?php if($this->gs->deposit_allow) { ?>
    <?php $form = $this->beginWidget('ActiveForm', array(
            'id' => 'cabinet-deposit-form',
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            )
        )) ?>

        <div class="alert alert-info">
            <p><?php echo Yii::t('main', 'Итоговая стоимость может незначительно отличаться в зависимости от способа оплаты.') ?></p>
        </div>

        <?php echo $form->errorSummary($model) ?>

        <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'sum', array('class' => 'col-lg-3 control-label')) ?>
            <div class="field">
                <?php echo $form->textField($model, 'sum', array('class' => 'form-control')) ?>
                <p class="help-block"><?php echo Yii::t('main', 'Стоимость 1 :a = :b :c', array(':a' => $this->gs->currency_name, ':b' => $this->gs->deposit_course_payments, ':c' => $this->gs->getCurrencySymbolName())) ?></p>
            </div>
        </div>

        <div class="button-group center">
            <button type="submit" class="button">
                <span><?php echo Yii::t('main', 'Далее') ?></span>
            </button>
        </div>

    <?php $this->endWidget() ?>
<?php } else { ?>
    <div class="alert alert-info"><?php echo Yii::t('main', 'Пополнение баланса отключено.') ?></div>
<?php } ?>
