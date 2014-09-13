<?php
/**
 * @var NewsController $this
 * @var News $model
 */

$title__ = Yii::t('backend', 'Новости');
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
        <?php echo $form->labelEx($model, 'description', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($model, 'description', array('placeholder' => $model->getAttributeLabel('description'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'text', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textArea($model, 'text', array('placeholder' => $model->getAttributeLabel('text'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_title', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_title', array('placeholder' => $model->getAttributeLabel('seo_title'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_description', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_description', array('placeholder' => $model->getAttributeLabel('seo_description'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'seo_keywords', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'seo_keywords', array('placeholder' => $model->getAttributeLabel('seo_keywords'), 'class' => 'form-control')) ?>
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
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'add' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>

<?php echo tinymce(array('News_text', 'News_description')) ?>