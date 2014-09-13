<?php
/**
 * @var UsersController $this
 * @var UserMessages $model
 * @var ActiveForm $form
 */
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'add-bonus-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal js-add-message-form',
    )
)) ?>

    <p class="help-block"><span class="required">*</span> <?php echo Yii::t('backend', 'После отправки сообщения, юзер его увидит при первом посещении сайта, обратный ответ он написать не сможет.') ?></p>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'message', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->textArea($model, 'message', array('placeholder' => $model->getAttributeLabel('message'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('main', 'Отправить') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>