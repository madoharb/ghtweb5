<?php
/**
 * @var UsersController $this
 * @var UserBonuses $model
 * @var ActiveForm $form
 */
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => 'add-bonus-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal js-add-bonus-form',
    )
)) ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'bonus_id', array('class' => 'col-sm-3 control-label')) ?>
        <div class="col-sm-9">
            <?php echo $form->dropDownList($model, 'bonus_id', CHtml::listData(Bonuses::model()->opened()->findAll(), 'id', 'title'), array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo Yii::t('main', 'Добавить') ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>