<?php
$title__ = Yii::t('backend', 'Бонус - коды');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/' . $this->getId() . '/codes'),
    (request()->getParam('id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление'))
) ?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'code', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'code', array('placeholder' => $model->getAttributeLabel('code'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'К примеру') ?>: 1111-MP09-SREW-MP5S <a class="js-generate-new-code"><?php echo Yii::t('backend', 'Сгенерировать код') ?></a></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'bonus_id', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'bonus_id', CHtml::listData(Bonuses::model()->findAll(), 'id', 'title'), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'limit', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'limit', array('placeholder' => $model->getAttributeLabel('limit'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Сколько раз можно активировать этот код пользователю') ?></p>
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


<script>
(function(){
    'use strict';
    $(function(){
        $('.js-generate-new-code').on('click',function(){
            $.get('<?php echo $this->createUrl('/backend/bonuses/generateCode') ?>',function(response){
                $('#BonusCodes_code').val(response);
            });
        });
    });
})();
</script>

