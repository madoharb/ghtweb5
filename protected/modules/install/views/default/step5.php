<?php
/**
 * @var Step5Form $model
 * @var ActiveForm $form
 */
?>

<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 5, настройка подключения к БД игрового сервера') ?></h1>
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
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'name', array('placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'ip', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'ip', array('placeholder' => $model->getAttributeLabel('ip'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'port', array('placeholder' => $model->getAttributeLabel('port'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Default: 7777') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_host', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_host', array('placeholder' => $model->getAttributeLabel('db_host'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_port', array('placeholder' => $model->getAttributeLabel('db_port'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Default: 3306') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_user', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_user', array('placeholder' => $model->getAttributeLabel('db_user'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_pass', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_pass', array('placeholder' => $model->getAttributeLabel('db_pass'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_name', array('placeholder' => $model->getAttributeLabel('db_name'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'version', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'version', app()->params['server_versions'], array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('backend', 'Далее') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>
