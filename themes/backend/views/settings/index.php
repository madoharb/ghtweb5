<?php
$this->pageTitle = 'Настройки';
$this->breadcrumbs = array('Настройки');

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.' . themeName() . '.assets'), FALSE, -1, YII_DEBUG);

js($assetsUrl . '/js/config.js', CClientScript::POS_END);
js($assetsUrl . '/js/serializeForm.js', CClientScript::POS_END);
?>


<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
)) ?>

    <ul class="nav nav-tabs">
        <?php foreach($model as $i => $group) { ?>
            <li<?php echo ($i == 0 ? ' class="active"' : '') ?>><a href="#config-<?php echo $i ?>" data-toggle="tab"><?php echo e($group->name) ?></a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <?php foreach($model as $i => $group) { ?>
            <div class="tab-pane fade<?php echo ($i == 0 ? ' in active' : '') ?>" id="config-<?php echo $i ?>">
                <?php foreach($group->config as $config) { ?>
                    <div class="form-group">
                        <label class="control-label">
                            <?php echo e($config->label) ?>
                            <a class="glyphicon glyphicon-retweet" title="<?php echo Yii::t('main', 'Отменить') ?>" rel="tooltip" data-fieldname="<?php echo $config->param ?>"></a>
                        </label>
                        <?php echo $config->getField() ?>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo Yii::t('main', 'Сохранить') ?></button>
                </div>
            </div>
        <?php } ?>
    </div>

<?php $this->endWidget() ?>
