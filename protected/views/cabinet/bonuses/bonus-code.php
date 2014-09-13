<?php
$title_ = Yii::t('main', 'Активация бонус кода');
$this->pageTitle = $title_;
$this->breadcrumbs=array($title_);
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'bonus-code-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group clearfix">
        <?php echo $form->labelEx($model, 'code', array('class' => 'col-lg-3 control-label')) ?>
        <div class="field">
            <?php echo $form->textField($model, 'code', array('placeholder' => $model->getAttributeLabel('code'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="button-group center">
        <button type="submit" class="button">
            <span><?php echo Yii::t('main', 'Активировать бонус код') ?></span>
        </button>
    </div>

<?php $this->endWidget() ?>