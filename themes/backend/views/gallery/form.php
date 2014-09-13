<?php
// Fancybox
css('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css');
js('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js', CClientScript::POS_END);


$title__ = Yii::t('backend', 'Галерея');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/' . $this->getId() . '/index'),
    ($this->getAction()->id == 'add' ? Yii::t('backend', 'Добавление') : Yii::t('backend', 'Редактирование'))
)
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',
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
        <?php echo $form->labelEx($model, 'img', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php if(!$model->isNewRecord && $model->imgIsExists()) { ?>
                <a href="<?php echo $model->getImgUrl() ?>" class="fancybox img-thumbnail">
                    <?php echo CHtml::image($model->getThumbUrl()) ?>
                </a>
                <br>
                <br>
            <?php } ?>
            <?php echo $form->fileField($model, 'img') ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'sort', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sort', array('placeholder' => $model->getAttributeLabel('sort'), 'class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'add' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
            <?php echo CHtml::link(Yii::t('backend', 'назад'), array('/backend/' . $this->getId() . '/index')) ?>
        </div>
    </div>

<?php $this->endWidget() ?>