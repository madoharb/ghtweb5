<?php
/**
 * @var SecurityController $this
 * @var UserProfiles $model
 */

$title__ = Yii::t('main', 'Безопасность');
$this->pageTitle = $title__;

$this->breadcrumbs=array($title__);
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'security-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <div class="hint"><?php echo Yii::t('main', 'Ваш текущий IP адрес: :ip', array(':ip' => '<b>' . userIp() . '</b>')) ?></div>

    <div class="alert alert-info">
        <?php echo Yii::t('main', 'Вы можете привязать Ваш аккаунт на сайте к определенному IP адресу или нескольким IP адресам.') ?><br>
        <span class="required">*</span> <?php echo Yii::t('main', 'Пустое поле отключает привязку к IP') ?>
    </div>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'protected_ip') ?>
        <div class="field">
            <?php echo $form->textArea($model, 'protected_ip', array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('main', 'Если хотите ввести несколько IP адресов то каждый IP должен быть с новой строки.') ?></p>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span><?php echo Yii::t('main', 'Сохранить') ?></span>
        </button>
    </div>

<?php $this->endWidget() ?>