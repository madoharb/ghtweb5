<?php
/**
 * @var TicketsController $this
 * @var ActiveForm $form
 * @var TicketsCategories $model
 */

$title__ = Yii::t('backend', 'Тикеты - категории');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/tickets/categories'),
    (request()->getParam('category_id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление'))
)
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'tickets-category-form',
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
        <?php echo $form->labelEx($model, 'sort', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sort', array('placeholder' => $model->getAttributeLabel('sort'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo (request()->getParam('category_id') ? Yii::t('backend', 'Сохранить') : Yii::t('backend', 'Создать')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>