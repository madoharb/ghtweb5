<?php
/**
 * @var BackendBaseController $this
 * @var LoginForm $model
 * @var ActiveForm $form
 */
?>

<?php $form = $this->beginWidget('ActiveForm') ?>
    <?php echo $form->errorSummary($model) ?>
    <?php echo $form->textField($model, 'login', array('placeholder' => 'Enter login')) ?><br>
    <?php echo $form->passwordField($model, 'password', array('placeholder' => 'Enter password')) ?><br>
    <button type="submit">Login</button>
<?php $this->endWidget() ?>
