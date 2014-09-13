<?php
/**
 * @var UsersController $this
 * @var EditUserForm $formModel
 * @var Users $userModel
 * @var ActiveForm $form
 */
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'edit-data-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal js-edit-data-form',
    )
)) ?>

    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'role', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->dropDownList($formModel, 'role', $formModel->getRoleList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'activated', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->dropDownList($formModel, 'activated', $formModel->getActivatedStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'vote_balance', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->textField($formModel, 'vote_balance', array('placeholder' => $formModel->getAttributeLabel('vote_balance'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'balance', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->textField($formModel, 'balance', array('placeholder' => $formModel->getAttributeLabel('balance'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'phone', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->textField($formModel, 'phone', array('placeholder' => $formModel->getAttributeLabel('phone'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($formModel, 'protected_ip', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->textArea($formModel, 'protected_ip', array('placeholder' => $formModel->getAttributeLabel('protected_ip'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Привязка к IP адресу(ам), новый IP с новой строки') ?></p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('main', 'Сохранить') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>