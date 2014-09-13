<?php
/**
 * @var Controller $this
 * @var RegisterForm $model
 */

$title_ = Yii::t('main', 'Регистрация');
$this->pageTitle = $title_;
?>

<div class="inner">
    <h2 class="title register"><?php echo e($title_) ?></h2>

    <?php $form = $this->beginWidget('ActiveForm', array(
        'id' => 'register-form',
        'htmlOptions' => array(
            'class' => 'form-horizontal',
        )
    )) ?>

        <?php echo $form->errorSummary($model) ?>

        <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

        <?php if(count($model->gs_list) > 1) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'gs_id') ?>
                <div class="field">
                    <?php echo $form->dropDownList($model, 'gs_id', CHtml::listData($model->gs_list, 'id', 'name'), array('class' => 'form-control')) ?>
                </div>
            </div>
        <?php } ?>

        <?php if(config('prefixes.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'prefix') ?>
                <div class="field">
                    <?php echo $form->dropDownList($model, 'prefix', $model->getPrefixes(), array('class' => 'form-control')) ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'login') ?>
            <div class="field">
                <?php echo $form->textField($model, 'login', array('placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control')) ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'password') ?>
            <div class="field">
                <?php echo $form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control')) ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 're_password') ?>
            <div class="field">
                <?php echo $form->passwordField($model, 're_password', array('placeholder' => $model->getAttributeLabel('re_password'), 'class' => 'form-control')) ?>
            </div>
        </div>

        <div class="form-group clearfix">
            <?php echo $form->labelEx($model, 'email') ?>
            <div class="field">
                <?php echo $form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control')) ?>
            </div>
        </div>

        <?php if(config('referral_program.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'referer') ?>
                <div class="field">
                    <?php echo $form->textField($model, 'referer', array('placeholder' => $model->getAttributeLabel('referer'), 'class' => 'form-control')) ?>
                </div>
            </div>
        <?php } ?>

        <?php if(CCaptcha::checkRequirements() && config('register.captcha.allow')) { ?>
            <div class="form-group clearfix">
                <?php echo $form->labelEx($model, 'verifyCode') ?>
                <div class="field captcha">
                    <?php echo $form->textField($model, 'verifyCode', array('placeholder' => $model->getAttributeLabel('verifyCode'), 'class' => 'form-control')) ?>
                    <div class="captcha-image">
                        <?php $this->widget('CCaptcha', array(
                            'id' => 'register-form-captcha'
                        )) ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="button-group center">
            <button type="submit" class="button">
                <span><?php echo Yii::t('main', 'Зарегистрироваться') ?></span>
            </button>
        </div>

    <?php $this->endWidget() ?>

</div>


