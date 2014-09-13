<?php
/**
 * @var DepositController $this
 * @var DepositForm $model
 * @var Deposit $deposit
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.views.assets'), FALSE, -1, YII_DEBUG);

$title_ = Yii::t('main', 'Пополнение баланса');
$this->pageTitle = $title_;

$this->breadcrumbs = array($title_);

js($assetsUrl . '/js/cabinet/deposit/sms.js', CClientScript::POS_END);
?>


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

<br><br>

<?php if($isSms) { ?>

    <h2>SMS пополнение</h2>

    <?php
    $prefix = '';
    if($this->gs->deposit_payment_system == Deposit::PAYMENT_SYSTEM_WAYTOPAY)
    {
        $prefix = config('waytopay.sms.prefix') . ' ' . user()->getId() . ' ' . user()->getGsId();
    }
    elseif($this->gs->deposit_payment_system == Deposit::PAYMENT_SYSTEM_UNITPAY)
    {
        $prefix = '' . user()->getId() . ' ' . user()->getGsId();
    }
    ?>

    <script>
        var smsList = <?php echo json_encode($smsList) ?>,
            itemName = '<?php echo CHtml::encode($this->gs->currency_name) ?>',
            prefix = '<?php echo $prefix ?>';
    </script>

    <div class="sms-block">
        <div class="sms-block-country">
            Страна: <?php echo CHtml::dropDownList('countries', '', $smsCountries, array('class' => 'js-countries not-chosen', 'prompt' => '-- выбрать --')) ?><br>
        </div>
        <div class="sms-block-operators">
            Оператор: <?php echo CHtml::dropDownList('operators', '', array(), array('class' => 'js-operators not-chosen', 'prompt' => '-- выбрать --')) ?><br>
        </div>
        <div class="sms-block-rates">
            Номинал: <?php echo CHtml::dropDownList('rates', '', array(), array('class' => 'js-rates not-chosen', 'prompt' => '-- выбрать --')) ?><br>
        </div>
        <div class="sms-block-info">
            Отправьте SMS на номер <span class="sms-block-number"></span> с текстом <span class="sms-block-text"></span><br>
            * между словами один пробел<br>
            * стоимость SMS сообщения <span class="sms-block-cost"></span> с НДС
        </div>
    </div>

<?php } ?>
