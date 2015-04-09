<?php
/**
 * @var LoginServersController $this
 * @var Ls $model
 * @var ActiveForm $form
 */

$title__ = Yii::t('backend', 'Логин сервера');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/loginServers/index'),
    (request()->getParam('id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление')),
);
?>


<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <legend><?php echo Yii::t('backend', 'Разное') ?></legend>

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
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'version', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'version', app()->params['server_versions'],array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'password_type', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'password_type', $model->getPasswordTypeList(), array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Тип шифрования пароля') ?></p>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Подключение к базе данных') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_host', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_host', array('placeholder' => $model->getAttributeLabel('db_host'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'IP адрес БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_port', array('placeholder' => $model->getAttributeLabel('db_port'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Порт от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_user', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_user', array('placeholder' => $model->getAttributeLabel('db_user'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Пользователь от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_pass', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'db_pass', array('placeholder' => $model->getAttributeLabel('db_pass'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Пароль от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_name', array('placeholder' => $model->getAttributeLabel('db_name'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Название БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'add' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>