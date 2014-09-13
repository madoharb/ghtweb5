<?php
/**
 * @var GameServersController $this
 * @var ShopCategories $model
 * @var Gs $gs
 */

$title__ = Yii::t('backend', 'Создание категории');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    Yii::t('backend', 'Сервера') => array('/backend/gameServers/index'),
    $gs->name . ' - ' . Yii::t('backend', 'Магазин') => array('/backend/gameServers/shop', 'gs_id' => $gs->id),
    ($this->getAction()->id == 'shopCategoryCreate' ? Yii::t('backend', 'Создание категории') : Yii::t('backend', 'Редактирование категории'))
) ?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php if($this->getAction()->id != 'shopCategoryCreate') { ?>
        <input type="hidden" name="old_name" value="<?php echo $model->name ?>">
        <input type="hidden" name="old_link" value="<?php echo $model->link ?>">
    <?php } ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'name', array('placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('main', 'То как будет называться категория в магазине') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'link', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'link', array('placeholder' => $model->getAttributeLabel('link'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('main', 'Разрешенные символы <b>:chars</b>', array(':chars' => ShopCategories::LINK_PATTERN)) ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'sort', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sort', array('placeholder' => $model->getAttributeLabel('sort'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('main', 'Порядок сортировки категорий в магазине') ?></p>
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
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'shopCategoryCreate' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
            <?php echo CHtml::link(Yii::t('backend', 'назад'), array('/backend/gameServers/shop', 'gs_id' => $gs->id)) ?>
        </div>
    </div>

<?php $this->endWidget() ?>