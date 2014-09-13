<?php
$title__ = Yii::t('backend', 'Наборы');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    Yii::t('backend', 'Сервера') => array('/backend/gameServers/index'),
    $gs->name . ' - ' . Yii::t('backend', 'Магазин') => array('/backend/gameServers/shop', 'gs_id' => $gs->id),
    Yii::t('backend', 'Наборы для категории - :category_name', array(':category_name' => $category->name)) => array('/backend/gameServers/shopCategoryPacks', 'gs_id' => $gs->id, 'category_id' => $category->id),
    ($this->getAction()->id == 'shopCategoryCreatePack' ? Yii::t('backend', 'Создание набора') : Yii::t('backend', 'Редактирование набора'))
);
clientScript()->registerScript('1', '
    var delImage = function(e){
        e.preventDefault();
        var $self;
        $self = $(this);
        APP.globalAjaxLoading("start");
        $.getJSON($self[0].href).done(function(response){
            APP.globalAjaxLoading("stop");
            if(response.status == "success") {
                $self.parents(".col-lg-9").find("img").remove().end().find("br").remove();
                $self.remove();
            }
        }).error(function(){
            APP.globalAjaxLoading("stop");
            $self.parents(".col-lg-9").find("img").css("border","1px solid red");
        });
    };
    $(".js-del-image").on("click", delImage);
');
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class'   => 'form-horizontal',
        'enctype' => 'multipart/form-data',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php if($this->getAction()->id != 'shopCategoryCreatePack') { ?>
        
    <?php } ?>

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
        <?php echo $form->labelEx($model, 'img', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php if(!$model->isNewRecord && $model->imgIsExists()) { ?>
                <?php echo CHtml::image($model->getImgUrl()) ?><br>
                <?php echo CHtml::link(Yii::t('main', 'Удалить картинку'), array('/backend/gameServers/shopCategoryPackDelImage', 'gs_id' => $gs->getPrimaryKey(), 'category_id' => $category->getPrimaryKey(), 'pack_id' => $model->getPrimaryKey()), array('class' => 'js-del-image')) ?><br><br>
            <?php } ?>
            <?php echo $form->fileField($model, 'img') ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo ($this->getAction()->id == 'shopCategoryCreatePack' ? Yii::t('backend', 'Создать') : Yii::t('backend', 'Сохранить')) ?></button>
            <?php echo CHtml::link(Yii::t('backend', 'назад'), array('/backend/gameServers/shopCategoryPacks', 'gs_id' => $gs->id, 'category_id' => $category->id)) ?>
        </div>
    </div>

<?php $this->endWidget() ?>

<?php echo tinymce(array('ShopItemsPacks_description')) ?>