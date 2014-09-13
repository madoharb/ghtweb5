<?php
/**
 * @var TicketsController $this
 * @var TicketsForm $model
 * @var Tickets $ticketModel
 */

$title_ = Yii::t('main', 'Поддержка - создание тикета');
$this->pageTitle = $title_;
$this->breadcrumbs = array(
    Yii::t('main', 'Поддержка') => array('/cabinet/tickets/index'),
    Yii::t('main', 'Создание тикета')
) ?>


<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'category_id', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->dropDownList($model, 'category_id', CHtml::listData(TicketsCategories::model()->opened()->findAll(), 'id', 'title'), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'priority', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->dropDownList($model, 'priority', $ticketModel->getPrioritiesList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'date_incident', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->textField($model, 'date_incident', array('placeholder' => $model->getAttributeLabel('date_incident'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('main', 'К примеру: :time', array(':time' => date('Y-m-d H:i'))) ?></p>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'char_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->textField($model, 'char_name', array('placeholder' => $model->getAttributeLabel('char_name'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'title', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->textField($model, 'title', array('placeholder' => $model->getAttributeLabel('title'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'text', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->textArea($model, 'text', array('placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span><?php echo Yii::t('main', 'Создать') ?></span>
        </button>
    </div>

<?php $this->endWidget() ?>
