<?php
/**
 * @var BonusesController $this
 * @var ActiveForm $form
 * @var Bonuses $model
 */

$title__ = Yii::t('backend', 'Бонусы');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/' . $this->getId() . '/index'),
    (request()->getParam('id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление'))
)
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'title', array('placeholder' => $model->getAttributeLabel('title'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'date_end', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'date_end', array('placeholder' => $model->getAttributeLabel('date_end'), 'class' => 'form-control', 'data-date-format' => 'YYYY-MM-DD hh:mm:ss')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Пример: :date', array(':date' => date('Y-m-d H:i:s'))) ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo (!request()->getParam('id') ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>

<?php $this->widget('application.widgets.DatetimePicker.DatetimePicker', array(
    'fields' => array('#Bonuses_date_end'),
)) ?>