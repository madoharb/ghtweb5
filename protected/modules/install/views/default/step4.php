<?php
/**
 * @var Step4Form $model
 * @var ActiveForm $form
 */
?>

<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 4, создание админа') ?></h1>
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
        <?php echo $form->labelEx($model, 'login', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'login', array('placeholder' => $model->getAttributeLabel('login'), 'class' => 'form-control')) ?>
            <p class="help-block">
                <?php echo Yii::t('main', 'Разрешенные символы: :chars', array(':chars' => Users::LOGIN_REGEXP)) ?><br>
                <?php echo Yii::t('install', 'Длина логина от :min до :max символов', array(':min' => Users::LOGIN_MIN_LENGTH, ':max' => Users::LOGIN_MAX_LENGTH)) ?>
            </p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'password', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('install', 'Длина пароля от :min до :max символов', array(':min' => Users::PASSWORD_MIN_LENGTH, ':max' => Users::PASSWORD_MAX_LENGTH)) ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'email', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'email', array('placeholder' => $model->getAttributeLabel('email'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('backend', 'Завершить установку') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>
