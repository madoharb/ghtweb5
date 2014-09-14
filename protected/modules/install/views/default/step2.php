<?php
/**
 * @var ActiveForm $form
 */
?>

<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 2, настройка подключения к БД сайта') ?></h1>
</div>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'mysql_host', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'mysql_host', array('placeholder' => $model->getAttributeLabel('mysql_host'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Default: 127.0.0.1') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'mysql_port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'mysql_port', array('placeholder' => $model->getAttributeLabel('mysql_port'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Default: 3306') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'mysql_user', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'mysql_user', array('placeholder' => $model->getAttributeLabel('mysql_user'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Default: root') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'mysql_pass', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'mysql_pass', array('placeholder' => $model->getAttributeLabel('mysql_pass'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Разрешены все символы кроме <b>\' \\</b>') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'mysql_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'mysql_name', array('placeholder' => $model->getAttributeLabel('mysql_name'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('backend', 'Далее') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>

